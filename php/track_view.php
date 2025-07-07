<?php
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    exit("DB connection failed");
}

$userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
$duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;

if ($productId && $duration > 0) {
    $stmt = $conn->prepare("
        INSERT INTO site_analytics (user_id, product_id, duration_seconds, timestamp)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("iii", $userId, $productId, $duration);
    $stmt->execute();
}

$conn->close();
?>
