<?php
require "db.php";
header("Content-Type: application/json");

$sessionToken = trim($_GET["session_id"] ?? "");

if ($sessionToken === "") {
    echo json_encode(["success" => false, "messages" => []]);
    exit;
}

$sessionSql = "SELECT id FROM chat_sessions WHERE session_token = ?";
$sessionStmt = mysqli_prepare($conn, $sessionSql);
mysqli_stmt_bind_param($sessionStmt, "s", $sessionToken);
mysqli_stmt_execute($sessionStmt);
$result = mysqli_stmt_get_result($sessionStmt);
$session = mysqli_fetch_assoc($result);

if (!$session) {
    echo json_encode(["success" => false, "messages" => []]);
    exit;
}

$msgSql = "SELECT id, sender_type, message, created_at FROM chat_messages WHERE chat_session_id = ? ORDER BY id ASC";
$msgStmt = mysqli_prepare($conn, $msgSql);
mysqli_stmt_bind_param($msgStmt, "i", $session["id"]);
mysqli_stmt_execute($msgStmt);
$msgResult = mysqli_stmt_get_result($msgStmt);

$messages = [];
while ($row = mysqli_fetch_assoc($msgResult)) {
    $messages[] = $row;
}

echo json_encode([
    "success" => true,
    "messages" => $messages
]);