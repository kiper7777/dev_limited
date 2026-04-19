<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

$serviceName = trim($_POST['service_name'] ?? '');
$amount = (float)($_POST['amount'] ?? 0);
$paymentStatus = trim($_POST['payment_status'] ?? 'pending');

if ($serviceName === '' || $amount <= 0) {
    json_response([
        'success' => false,
        'message' => 'Invalid payment request.'
    ]);
}

if (!in_array($paymentStatus, ['pending', 'paid'], true)) {
    $paymentStatus = 'pending';
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO payments (user_id, service_name, amount, status, payment_method)
     VALUES (?, ?, ?, ?, 'manual_request')"
);
mysqli_stmt_bind_param($stmt, "isds", $userId, $serviceName, $amount, $paymentStatus);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    $title = $paymentStatus === 'paid' ? 'Payment completed' : 'Payment request created';
    $body = $paymentStatus === 'paid'
        ? "Payment for {$serviceName} (£{$amount}) has been recorded."
        : "Payment request for {$serviceName} (£{$amount}) has been created.";

    $n = mysqli_prepare($conn, "INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($n, "iss", $userId, $title, $body);
    mysqli_stmt_execute($n);

    $admins = mysqli_query($conn, "SELECT id FROM users WHERE role = 'admin'");
    while ($admin = mysqli_fetch_assoc($admins)) {
        $adminTitle = $paymentStatus === 'paid' ? 'New paid service' : 'New payment request';
        $adminBody = "User #{$userId} created a {$paymentStatus} record for {$serviceName} (£{$amount}).";
        $na = mysqli_prepare($conn, "INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($na, "iss", $admin['id'], $adminTitle, $adminBody);
        mysqli_stmt_execute($na);
    }
}

json_response([
    'success' => $ok,
    'message' => $ok
        ? ($paymentStatus === 'paid' ? 'Payment recorded successfully.' : 'Payment request created successfully.')
        : 'Failed to create payment request.'
]);