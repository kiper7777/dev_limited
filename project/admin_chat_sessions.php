<?php
require "db.php";
header("Content-Type: application/json");

$sql = "SELECT session_token, created_at, status FROM chat_sessions ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$sessions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sessions[] = $row;
}

echo json_encode([
    "sessions" => $sessions
]);