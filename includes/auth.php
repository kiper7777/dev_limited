<?php
require_once __DIR__ . '/db.php';

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /project/login.php');
        exit;
    }
}

function require_admin() {
    require_login();
    if (($_SESSION['role'] ?? 'client') !== 'admin') {
        http_response_code(403);
        exit('Access denied');
    }
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}