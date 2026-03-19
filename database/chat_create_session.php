<?php
require "db.php";
header("Content-Type: application/json");

$token = bin2hex(random_bytes(16));
$userId = $_SESSION["user_id"] ?? null;

$sql = "INSERT INTO chat_sessions (session_token, user_id) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $token, $userId);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    echo json_encode([
        "success" => true,
        "session_id" => $token
    ]);
} else {
    echo json_encode([
        "success" => false
    ]);
}