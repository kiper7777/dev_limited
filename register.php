<?php
require "db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($name === "" || $email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please fill in all fields."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);
    exit;
}

if (mb_strlen($name) < 2) {
    echo json_encode([
        "success" => false,
        "message" => "Name must be at least 2 characters."
    ]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode([
        "success" => false,
        "message" => "Password must be at least 6 characters."
    ]);
    exit;
}

$checkSql = "SELECT id FROM users WHERE email = ?";
$checkStmt = mysqli_prepare($conn, $checkSql);

if (!$checkStmt) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: failed to prepare email check."
    ]);
    exit;
}

mysqli_stmt_bind_param($checkStmt, "s", $email);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_store_result($checkStmt);

if (mysqli_stmt_num_rows($checkStmt) > 0) {
    mysqli_stmt_close($checkStmt);

    echo json_encode([
        "success" => false,
        "message" => "A user with this email already exists."
    ]);
    exit;
}

mysqli_stmt_close($checkStmt);

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$insertSql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertSql);

if (!$insertStmt) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: failed to prepare insert."
    ]);
    exit;
}

mysqli_stmt_bind_param($insertStmt, "sss", $name, $email, $hashedPassword);

if (mysqli_stmt_execute($insertStmt)) {
    echo json_encode([
        "success" => true,
        "message" => "Registration successful. You can now sign in."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed. Please try again."
    ]);
}

mysqli_stmt_close($insertStmt);
mysqli_close($conn);
?>