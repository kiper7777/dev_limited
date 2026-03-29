<?php
echo "DB connected successfully";
?>

<?php
require_once __DIR__ . '/config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    if (APP_DEBUG) {
        die('Database connection failed: ' . mysqli_connect_error());
    }
    die('Database connection failed.');
}

mysqli_set_charset($conn, 'utf8mb4');