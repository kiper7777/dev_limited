<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$sessionToken = trim($_GET["session_id"] ?? "");
$viewer = trim($_GET["viewer"] ?? "client");

if ($sessionToken === "") {
    json_response(["success" => false, "messages" => []]);
}

$sessionSql = "SELECT id FROM chat_sessions WHERE session_token = ?";
$sessionStmt = mysqli_prepare($conn, $sessionSql);
mysqli_stmt_bind_param($sessionStmt, "s", $sessionToken);
mysqli_stmt_execute($sessionStmt);
$result = mysqli_stmt_get_result($sessionStmt);
$session = mysqli_fetch_assoc($result);

if (!$session) {
    json_response(["success" => false, "messages" => []]);
}

$msgSql = "SELECT id, sender_type, message, file_path, file_name, created_at
           FROM chat_messages
           WHERE chat_session_id = ?
           ORDER BY id ASC";
$msgStmt = mysqli_prepare($conn, $msgSql);
mysqli_stmt_bind_param($msgStmt, "i", $session["id"]);
mysqli_stmt_execute($msgStmt);
$msgResult = mysqli_stmt_get_result($msgStmt);

$messages = [];
while ($row = mysqli_fetch_assoc($msgResult)) {
    $messages[] = $row;
}

if ($viewer === 'admin') {
    mysqli_query($conn, "UPDATE chat_sessions SET unread_for_admin = 0 WHERE id = " . (int)$session['id']);
} else {
    mysqli_query($conn, "UPDATE chat_sessions SET unread_for_user = 0 WHERE id = " . (int)$session['id']);
}

json_response([
    "success" => true,
    "messages" => $messages
]);