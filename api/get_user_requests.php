<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = current_user_id();

$sql = "SELECT * FROM project_requests WHERE user_id = ? ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$items = [];
while ($row = mysqli_fetch_assoc($res)) {
    $items[] = $row;
}

json_response(['success' => true, 'items' => $items]);