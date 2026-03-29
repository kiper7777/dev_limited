<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$sql = "SELECT cs.session_token, cs.user_name, cs.unread_for_admin, cs.status, cs.created_at, u.email
        FROM chat_sessions cs
        LEFT JOIN users u ON u.id = cs.user_id
        ORDER BY cs.unread_for_admin DESC, cs.id DESC";
$result = mysqli_query($conn, $sql);

$sessions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sessions[] = $row;
}

json_response(["success" => true, "sessions" => $sessions]);