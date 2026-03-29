<?php
function json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}