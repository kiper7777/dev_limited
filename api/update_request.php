<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = current_user_id();
$requestId = (int)($_POST['request_id'] ?? 0);
$projectName = trim($_POST['project_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$budgetRange = trim($_POST['budget_range'] ?? '');
$timeline = trim($_POST['timeline'] ?? '');

$sql = "UPDATE project_requests
        SET project_name = ?, description = ?, budget_range = ?, timeline = ?
        WHERE id = ? AND user_id = ? AND status NOT IN ('completed','cancelled')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssssii", $projectName, $description, $budgetRange, $timeline, $requestId, $userId);
$ok = mysqli_stmt_execute($stmt);

json_response(['success' => $ok]);