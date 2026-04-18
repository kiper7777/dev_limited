<?php

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function clean_output_buffers(): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
}

function json_response(array $data, int $statusCode = 200): void
{
    clean_output_buffers();
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function post(string $key, string $default = ''): string
{
    return trim($_POST[$key] ?? $default);
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}