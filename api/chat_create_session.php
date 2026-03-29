<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['user_name'] ?? 'Guest';

if ($userId) {
    $check = mysqli_prepare($conn, "SELECT session_token FROM chat_sessions WHERE user_id = ? AND status = 'open' ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($check, "i", $userId);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        json_response(['success' => true, 'session_id' => $row['session_token']]);
    }
}

$token = bin2hex(random_bytes(16));
$sql = "INSERT INTO chat_sessions (session_token, user_id, user_name, unread_for_admin, unread_for_user)
        VALUES (?, ?, ?, 0, 0)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sis", $token, $userId, $userName);
$ok = mysqli_stmt_execute($stmt);

json_response([
    'success' => $ok,
    'session_id' => $token
]);
