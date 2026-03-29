<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$sessionToken = trim($_POST['session_id'] ?? '');
if ($sessionToken === '' || !isset($_FILES['chat_file'])) {
    json_response(['success' => false, 'message' => 'Missing data.']);
}

$allowed = ['image/jpeg','image/png','image/webp','application/pdf'];
$file = $_FILES['chat_file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    json_response(['success' => false, 'message' => 'Upload failed.']);
}

if (!in_array($file['type'], $allowed, true)) {
    json_response(['success' => false, 'message' => 'Invalid file type.']);
}

$dir = __DIR__ . '/../assets/uploads/chat/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
$target = $dir . $safeName;

if (!move_uploaded_file($file['tmp_name'], $target)) {
    json_response(['success' => false, 'message' => 'Could not save file.']);
}

$sessionSql = "SELECT id FROM chat_sessions WHERE session_token = ?";
$sessionStmt = mysqli_prepare($conn, $sessionSql);
mysqli_stmt_bind_param($sessionStmt, "s", $sessionToken);
mysqli_stmt_execute($sessionStmt);
$res = mysqli_stmt_get_result($sessionStmt);
$session = mysqli_fetch_assoc($res);

if (!$session) {
    json_response(['success' => false, 'message' => 'Chat session not found.']);
}

$relativePath = 'assets/uploads/chat/' . $safeName;
$userId = current_user_id();

$insert = mysqli_prepare($conn, "INSERT INTO chat_messages (chat_session_id, sender_type, sender_user_id, file_path, file_name) VALUES (?, 'client', ?, ?, ?)");
mysqli_stmt_bind_param($insert, "iiss", $session['id'], $userId, $relativePath, $file['name']);
$ok = mysqli_stmt_execute($insert);

mysqli_query($conn, "UPDATE chat_sessions SET unread_for_admin = unread_for_admin + 1 WHERE id = " . (int)$session['id']);

json_response(['success' => $ok, 'file_path' => $relativePath, 'file_name' => $file['name']]);