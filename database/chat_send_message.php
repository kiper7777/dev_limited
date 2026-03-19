<?php
require "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$sessionToken = trim($data["session_id"] ?? "");
$senderType = trim($data["sender_type"] ?? "");
$message = trim($data["message"] ?? "");

if ($sessionToken === "" || $senderType === "" || $message === "") {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

$sessionSql = "SELECT id FROM chat_sessions WHERE session_token = ?";
$sessionStmt = mysqli_prepare($conn, $sessionSql);
mysqli_stmt_bind_param($sessionStmt, "s", $sessionToken);
mysqli_stmt_execute($sessionStmt);
$result = mysqli_stmt_get_result($sessionStmt);
$session = mysqli_fetch_assoc($result);

if (!$session) {
    echo json_encode(["success" => false, "message" => "Session not found."]);
    exit;
}

$insertSql = "INSERT INTO chat_messages (chat_session_id, sender_type, message) VALUES (?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertSql);
mysqli_stmt_bind_param($insertStmt, "iss", $session["id"], $senderType, $message);
$ok = mysqli_stmt_execute($insertStmt);

echo json_encode(["success" => $ok]);