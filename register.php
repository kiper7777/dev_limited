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

$name = post('name');
$email = post('email');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
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

if (mb_strlen($name) < 2) {
    json_response([
        'success' => false,
        'message' => 'Name must be at least 2 characters.'
    ], 422);
}

if (strlen($password) < 6) {
    json_response([
        'success' => false,
        'message' => 'Password must be at least 6 characters.'
    ], 422);
}

/*
|--------------------------------------------------------------------------
| Check if email exists
|--------------------------------------------------------------------------
*/
$checkSql = "SELECT id FROM users WHERE email = ? LIMIT 1";
$checkStmt = mysqli_prepare($conn, $checkSql);

if (!$checkStmt) {
    json_response([
        'success' => false,
        'message' => 'Database error: failed to prepare email check.',
        'debug' => APP_DEBUG ? mysqli_error($conn) : null
    ], 500);
}

mysqli_stmt_bind_param($checkStmt, 's', $email);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_store_result($checkStmt);

if (mysqli_stmt_num_rows($checkStmt) > 0) {
    mysqli_stmt_close($checkStmt);

    json_response([
        'success' => false,
        'message' => 'A user with this email already exists.'
    ], 409);
}

mysqli_stmt_close($checkStmt);

/*
|--------------------------------------------------------------------------
| Insert new user
|--------------------------------------------------------------------------
*/
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$role = 'client';

$insertSql = "INSERT INTO users (role, name, email, password, email_verified) VALUES (?, ?, ?, ?, 0)";
$insertStmt = mysqli_prepare($conn, $insertSql);

if (!$insertStmt) {
    json_response([
        'success' => false,
        'message' => 'Database error: failed to prepare insert.',
        'debug' => APP_DEBUG ? mysqli_error($conn) : null
    ], 500);
}

mysqli_stmt_bind_param($insertStmt, 'ssss', $role, $name, $email, $hashedPassword);
$ok = mysqli_stmt_execute($insertStmt);

if (!$ok) {
    json_response([
        'success' => false,
        'message' => 'Registration failed.',
        'debug' => APP_DEBUG ? mysqli_stmt_error($insertStmt) : null
    ], 500);
}

$userId = mysqli_insert_id($conn);

mysqli_stmt_close($insertStmt);

/*
|--------------------------------------------------------------------------
| Optional: auto login after registration
|--------------------------------------------------------------------------
*/
$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;
$_SESSION['role'] = 'client';

json_response([
    'success' => true,
    'message' => 'Registration successful. Welcome, ' . $name . '!',
    'redirect' => BASE_URL . '/dashboard/index.php'
]);