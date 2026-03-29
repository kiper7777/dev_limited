<?php
require_once __DIR__ . '/../includes/functions.php';

$payload = json_decode(file_get_contents('php://input'), true);
$message = strtolower(trim($payload['message'] ?? ''));

$faqPath = __DIR__ . '/../assets/faq.json';
$faq = file_exists($faqPath) ? json_decode(file_get_contents($faqPath), true) : [];

foreach ($faq as $item) {
    foreach ($item['keywords'] as $keyword) {
        if (str_contains($message, strtolower($keyword))) {
            json_response([
                'success' => true,
                'answer' => $item['answer']
            ]);
        }
    }
}

json_response([
    'success' => true,
    'answer' => 'I can help with pricing, dashboards, CRM, maintenance, timelines, admin panels and project requests.'
]);