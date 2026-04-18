<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$company = trim($_POST['company'] ?? '');
$budget = trim($_POST['budget'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    json_response([
        'success' => false,
        'message' => 'Please fill in all required fields.'
    ]);
}

$userId = $_SESSION['user_id'] ?? null;

$stmt = mysqli_prepare($conn, "
    INSERT INTO leads (user_id, source, full_name, email, phone, company_name, budget_range, message)
    VALUES (?, 'contact_form', ?, ?, ?, ?, ?, ?)
");
mysqli_stmt_bind_param($stmt, "issssss", $userId, $name, $email, $phone, $company, $budget, $message);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    $admins = mysqli_query($conn, "SELECT id FROM users WHERE role = 'admin'");
    while ($admin = mysqli_fetch_assoc($admins)) {
        $title = 'New contact lead';
        $body = "New lead from {$name} ({$email})";
        $n = mysqli_prepare($conn, "INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($n, "iss", $admin['id'], $title, $body);
        mysqli_stmt_execute($n);
    }
}

json_response([
    'success' => $ok,
    'message' => $ok ? 'Your request has been sent successfully.' : 'Failed to send your request.'
]);