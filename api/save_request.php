<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = current_user_id();

$projectName = trim($_POST['project_name'] ?? '');
$websiteType = trim($_POST['website_type'] ?? '');
$businessName = trim($_POST['business_name'] ?? '');
$industry = trim($_POST['industry'] ?? '');
$budgetRange = trim($_POST['budget_range'] ?? '');
$timeline = trim($_POST['timeline'] ?? '');
$targetAudience = trim($_POST['target_audience'] ?? '');
$preferredStyle = trim($_POST['preferred_style'] ?? '');
$description = trim($_POST['description'] ?? '');
$referenceSites = trim($_POST['reference_sites'] ?? '');
$features = $_POST['features'] ?? [];
$services = $_POST['services'] ?? [];

if ($projectName === '') {
    json_response(['success' => false, 'message' => 'Project name is required.']);
}

$sql = "INSERT INTO project_requests
(user_id, project_name, website_type, business_name, industry, budget_range, timeline, target_audience, preferred_style, description, reference_sites, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'submitted')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "issssssssss",
    $userId,
    $projectName,
    $websiteType,
    $businessName,
    $industry,
    $budgetRange,
    $timeline,
    $targetAudience,
    $preferredStyle,
    $description,
    $referenceSites
);
$ok = mysqli_stmt_execute($stmt);

if (!$ok) {
    json_response(['success' => false, 'message' => 'Failed to create request.']);
}

$requestId = mysqli_insert_id($conn);

foreach ($features as $featureId) {
    $featureId = (int)$featureId;
    $f = mysqli_prepare($conn, "INSERT INTO project_request_features (project_request_id, feature_option_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($f, "ii", $requestId, $featureId);
    mysqli_stmt_execute($f);
}

foreach ($services as $serviceId) {
    $serviceId = (int)$serviceId;
    $s = mysqli_prepare($conn, "INSERT INTO project_request_services (project_request_id, service_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($s, "ii", $requestId, $serviceId);
    mysqli_stmt_execute($s);
}

json_response(['success' => true, 'message' => 'Request submitted successfully.']);