<?php
// Database configuration
$host = 'localhost';
$dbname = 'thredqwx_threadline';
$username = 'thredqwx_admin';      // ✅ Your actual DB username
$password = 'Mostin2003$';         // ✅ Your actual DB password

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
