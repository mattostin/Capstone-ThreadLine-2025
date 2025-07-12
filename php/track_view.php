<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Connect to database
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    error_log("DB connection failed: " . $conn->connect_error);
    http_response_code(500);
    exit("Database connection failed.");
}

// Get raw JSON data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
    error_log("No valid JSON received.");
    http_response_code(400);
    exit("Invalid JSON payload.");
}

// Extract and sanitize values
$userId        = isset($data['user_id']) ? intval($data['user_id']) : null;
$productId     = isset($data['product_id']) ? intval($data['product_id']) : null;
$pageVisited   = isset($data['page_visited']) ? $conn->real_escape_string($data['page_visited']) : null;
$duration      = isset($data['duration_seconds']) ? intval($data['duration_seconds']) : 0;
$sessionStart  = isset($data['session_start']) ? $data['session_start'] : null;
$sessionEnd    = isset($data['session_end']) ? $data['session_end'] : null;

if (!$pageVisited || !$sessionStart || !$sessionEnd) {
    error_log("Missing required fields.");
    http_response_code(400);
    exit("Missing page_visited or timestamps.");
}

// Prepare and execute insert
$stmt = $conn->prepare("
    INSERT INTO site_analytics 
    (user_id, page_visited, product_id, session_start, session_end, duration_seconds, timestamp)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

if (!$stmt) {
    error_log("Statement preparation failed: " . $conn->error);
    http_response_code(500);
    exit("SQL prepare error.");
}

$stmt->bind_param("issssi", $userId, $pageVisited, $productId, $sessionStart, $sessionEnd, $duration);

if ($stmt->execute()) {
    http_response_code(200);
    echo "Tracking inserted";
} else {
    error_log("Insert failed: " . $stmt->error);
    http_response_code(500);
    echo "Insert failed";
}

$stmt->close();
$conn->close();
?>
