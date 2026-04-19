<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if (!is_post()) {
    json_response([
        'success' => false,
        'message' => 'Invalid request method.'
    ], 405);
}

$email = post('email');
$password = $_POST['password'] ?? '';
$rememberMe = (int)($_POST['remember_me'] ?? 0) === 1;

if ($email === '' || $password === '') {
    json_response([
        'success' => false,
        'message' => 'Please fill in all fields.'
    ], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ], 422);
}

$sql = "SELECT id, role, name, email, password FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    json_response([
        'success' => false,
        'message' => 'Database error: failed to prepare login query.',
        'debug' => APP_DEBUG ? mysqli_error($conn) : null
    ], 500);
}

mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    json_response([
        'success' => false,
        'message' => 'User not found.'
    ], 404);
}

if (!password_verify($password, $user['password'])) {
    json_response([
        'success' => false,
        'message' => 'Incorrect password.'
    ], 401);
}

session_regenerate_id(true);

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['role'] = $user['role'];

if ($rememberMe) {
    $rememberToken = bin2hex(random_bytes(32));
    $stmtToken = mysqli_prepare($conn, "UPDATE users SET remember_token = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmtToken, "si", $rememberToken, $user['id']);
    mysqli_stmt_execute($stmtToken);

    setcookie(
        'remember_token',
        $rememberToken,
        time() + (86400 * 30),
        '/',
        '',
        false,
        true
    );
}

json_response([
    'success' => true,
    'message' => 'Welcome, ' . $user['name'] . '!',
    'role' => $user['role'],
    'redirect' => $user['role'] === 'admin'
        ? BASE_URL . '/admin/dashboard.php'
        : BASE_URL . '/dashboard/index.php'
]);