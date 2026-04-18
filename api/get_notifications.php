<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$userId = current_user_id();

$res = mysqli_query($conn, "SELECT id, title, body, is_read, created_at FROM notifications WHERE user_id = {$userId} ORDER BY id DESC");
$items = [];
$unread = 0;

while ($row = mysqli_fetch_assoc($res)) {
    $items[] = $row;
    if ((int)$row['is_read'] === 0) {
        $unread++;
    }
}

json_response([
    'success' => true,
    'items' => $items,
    'unread' => $unread
]);