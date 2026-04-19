<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

$ok = mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = {$userId}");

json_response(['success' => (bool)$ok]);