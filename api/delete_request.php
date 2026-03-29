<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = current_user_id();
$requestId = (int)($_POST['request_id'] ?? 0);

$sql = "UPDATE project_requests SET status = 'cancelled' WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $requestId, $userId);
$ok = mysqli_stmt_execute($stmt);

json_response(['success' => $ok, 'message' => $ok ? 'Request cancelled.' : 'Failed to cancel request.']);