<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$data = json_decode(file_get_contents("php://input"), true);
$sessionToken = trim($data["session_id"] ?? "");
$senderType = trim($data["sender_type"] ?? "");
$message = trim($data["message"] ?? "");
$senderUserId = $_SESSION['user_id'] ?? null;

if ($sessionToken === "" || $senderType === "" || $message === "") {
    json_response(["success" => false, "message" => "Invalid input."]);
}

$sessionSql = "SELECT id FROM chat_sessions WHERE session_token = ?";
$sessionStmt = mysqli_prepare($conn, $sessionSql);
mysqli_stmt_bind_param($sessionStmt, "s", $sessionToken);
mysqli_stmt_execute($sessionStmt);
$result = mysqli_stmt_get_result($sessionStmt);
$session = mysqli_fetch_assoc($result);

if (!$session) {
    json_response(["success" => false, "message" => "Session not found."]);
}

$insertSql = "INSERT INTO chat_messages (chat_session_id, sender_type, sender_user_id, message, is_read_by_admin, is_read_by_user)
              VALUES (?, ?, ?, ?, 0, 0)";
$insertStmt = mysqli_prepare($conn, $insertSql);
mysqli_stmt_bind_param($insertStmt, "isis", $session["id"], $senderType, $senderUserId, $message);
$ok = mysqli_stmt_execute($insertStmt);

if ($senderType === 'client') {
    mysqli_query($conn, "UPDATE chat_sessions SET unread_for_admin = unread_for_admin + 1 WHERE id = " . (int)$session['id']);
} elseif ($senderType === 'operator') {
    mysqli_query($conn, "UPDATE chat_sessions SET unread_for_user = unread_for_user + 1 WHERE id = " . (int)$session['id']);
}

json_response(["success" => $ok]);