<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function current_user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

function current_user_role(): string
{
    return $_SESSION['role'] ?? 'guest';
}

function is_logged_in(): bool
{
    return current_user_id() !== null;
}

function is_admin(): bool
{
    return current_user_role() === 'admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('/login.php');
    }
}

function require_admin(): void
{
    require_login();

    if (!is_admin()) {
        http_response_code(403);
        exit('Access denied.');
    }
}