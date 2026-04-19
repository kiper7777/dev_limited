<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$res = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'admin' AND is_online = 1");
$row = mysqli_fetch_assoc($res);

json_response([
    'success' => true,
    'online' => ((int)$row['c'] > 0)
]);