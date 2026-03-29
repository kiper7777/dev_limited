<?php
require_once __DIR__ . '/includes/db.php';

$result = $conn->query("SHOW TABLES");

if (!$result) {
    die("Query error: " . $conn->error);
}

while ($row = $result->fetch_array()) {
    echo $row[0] . "<br>";
}