<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);

$fullName = trim($data['full_name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$company = trim($data['company_name'] ?? '');
$websiteType = trim($data['website_type'] ?? '');
$budget = trim($data['budget_range'] ?? '');
$timeline = trim($data['timeline'] ?? '');
$features = trim($data['required_features'] ?? '');
$message = trim($data['message'] ?? '');
$userId = $_SESSION['user_id'] ?? null;

if ($fullName === '' || $email === '') {
    json_response(['success' => false, 'message' => 'Name and email are required.']);
}

$sql = "INSERT INTO leads (user_id, source, full_name, email, phone, company_name, website_type, budget_range, timeline, required_features, message)
        VALUES (?, 'chatbot_quiz', ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "isssssssss",
    $userId,
    $fullName,
    $email,
    $phone,
    $company,
    $websiteType,
    $budget,
    $timeline,
    $features,
    $message
);
$ok = mysqli_stmt_execute($stmt);

json_response([
    'success' => $ok,
    'message' => $ok ? 'Lead saved successfully.' : 'Failed to save lead.'
]);