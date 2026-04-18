<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$requestId = (int)($_POST['request_id'] ?? 0);
$status = trim($_POST['status'] ?? 'submitted');

$stmt = mysqli_prepare($conn, "UPDATE project_requests SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $status, $requestId);
mysqli_stmt_execute($stmt);

/* уведомление клиенту */
$userRes = mysqli_query($conn, "
    SELECT user_id, project_name
    FROM project_requests
    WHERE id = {$requestId}
    LIMIT 1
");
if ($row = mysqli_fetch_assoc($userRes)) {
    $title = 'Request status updated';
    $body = "Your request '{$row['project_name']}' status is now '{$status}'.";
    $n = mysqli_prepare($conn, "INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($n, "iss", $row['user_id'], $title, $body);
    mysqli_stmt_execute($n);
}

header('Location: ' . BASE_URL . '/admin/requests.php');
exit;