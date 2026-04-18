<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$name = 'Admin';
$email = 'admin@devlimited.local';
$password = 'Admin123!';
$hash = password_hash($password, PASSWORD_BCRYPT);

$check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    echo "Admin already exists.";
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO users (role, name, email, password, email_verified) VALUES ('admin', ?, ?, ?, 1)");
mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin created successfully.";
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}