<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Base paths
|--------------------------------------------------------------------------
*/
define('BASE_URL', '/PHP/DevLimited');
define('PROJECT_ROOT', dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
*/
define('APP_ENV', 'local');
define('APP_DEBUG', true);

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/
define('DB_HOST', 'localhost');
define('DB_NAME', 'devin_limited');
define('DB_USER', 'root');
define('DB_PASS', '');

/*
|--------------------------------------------------------------------------
| Autologin using remember_token
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/db.php';

    $rememberToken = $_COOKIE['remember_token'];
    $stmt = mysqli_prepare($conn, "SELECT id, role, name, email FROM users WHERE remember_token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $rememberToken);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rememberUser = mysqli_fetch_assoc($result);

    if ($rememberUser) {
        $_SESSION['user_id'] = (int)$rememberUser['id'];
        $_SESSION['user_name'] = $rememberUser['name'];
        $_SESSION['user_email'] = $rememberUser['email'];
        $_SESSION['role'] = $rememberUser['role'];
    }
}