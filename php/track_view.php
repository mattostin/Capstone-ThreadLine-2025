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

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
    http_response_code(400);
    exit("No JSON data received");
}

$userId = isset($data['user_id']) ? intval($data['user_id']) : null;
$productId = isset($data['product_id']) ? intval($data['product_id']) : null;
$pageVisited = isset($data['page']) ? $data['page'] : null;
$duration = isset($data['duration_seconds']) ? intval($data['duration_seconds']) : 0;

if ($productId && $duration > 0) {
    $stmt = $conn->prepare("
        INSERT INTO site_analytics 
        (user_id, page_visited, product_id, session_start, session_end, duration_seconds, timestamp)
        VALUES (?, ?, ?, NOW(), NOW(), ?, NOW())
    ");
    $stmt->bind_param("isii", $userId, $pageVisited, $productId, $duration);
    $stmt->execute();
}

$conn->close();
?>
