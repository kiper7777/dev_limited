<?php
$servername = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'devin_limited';

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}