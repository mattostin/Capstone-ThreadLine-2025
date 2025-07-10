<?php
require_once 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['positions']) || !is_array($data['positions'])) {
  http_response_code(400);
  echo json_encode(["error" => "Invalid data"]);
  exit;
}

foreach ($data['positions'] as $item) {
  $stmt = $conn->prepare("UPDATE products SET position = ? WHERE id = ?");
  $stmt->execute([$item['position'], $item['id']]);
}

echo json_encode(["success" => true]);
