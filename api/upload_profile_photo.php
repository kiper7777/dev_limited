<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

if (!isset($_FILES['profile_photo'])) {
    json_response([
        'success' => false,
        'message' => 'No file uploaded.'
    ]);
}

$file = $_FILES['profile_photo'];
$allowed = ['image/jpeg', 'image/png', 'image/webp'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    json_response([
        'success' => false,
        'message' => 'Upload failed.'
    ]);
}

if (!in_array($file['type'], $allowed, true)) {
    json_response([
        'success' => false,
        'message' => 'Invalid file type.'
    ]);
}

$dir = __DIR__ . '/../assets/uploads/profile/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
$target = $dir . $safeName;

if (!move_uploaded_file($file['tmp_name'], $target)) {
    json_response([
        'success' => false,
        'message' => 'Could not save file.'
    ]);
}

$relativePath = 'assets/uploads/profile/' . $safeName;

$stmt = mysqli_prepare($conn, "UPDATE users SET profile_photo = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $relativePath, $userId);
$ok = mysqli_stmt_execute($stmt);

json_response([
    'success' => $ok,
    'message' => $ok ? 'Profile photo updated.' : 'Failed to update profile photo.',
    'photo_url' => $ok ? (BASE_URL . '/' . $relativePath) : null
]);