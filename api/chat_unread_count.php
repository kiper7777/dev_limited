<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$sessionToken = trim($_GET['session_id'] ?? '');

if ($sessionToken === '') {
    json_response(['success' => false, 'unread' => 0]);
}

$stmt = mysqli_prepare($conn, "SELECT unread_for_user FROM chat_sessions WHERE session_token = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $sessionToken);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

json_response([
    'success' => true,
    'unread' => isset($row['unread_for_user']) ? (int)$row['unread_for_user'] : 0
]);