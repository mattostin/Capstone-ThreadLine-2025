<?php
// Database credentials from your setup
$host = 'localhost';
$db   = 'thredqwx_threadline';
$user = 'thredqwx_admin';
$pass = 'Mostin2003$';

// Create a new MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Optional: Set character set
$mysqli->set_charset('utf8mb4');
?>
