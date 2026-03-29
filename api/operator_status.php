<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$userId = current_user_id();
$isOnline = (int)($_POST['is_online'] ?? 0);

$stmt = mysqli_prepare($conn, "UPDATE users SET is_online = ?, last_seen = NOW() WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ii", $isOnline, $userId);
$ok = mysqli_stmt_execute($stmt);

json_response(['success' => $ok]);