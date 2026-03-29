<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$requestId = (int)($_POST['request_id'] ?? 0);
$status = trim($_POST['status'] ?? 'submitted');

$stmt = mysqli_prepare($conn, "UPDATE project_requests SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $status, $requestId);
mysqli_stmt_execute($stmt);

header('Location: /project/admin/requests.php');
exit;