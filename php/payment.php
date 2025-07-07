<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['error' => 'Invalid request method']);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($_SESSION['user_id'])) {
  echo json_encode(['error' => 'Invalid cart data or not logged in']);
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}

$user_id = $_SESSION['user_id'];
$conn->begin_transaction();

try {
  $stmt_order = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
  $stmt_order->bind_param("i", $user_id);
  $stmt_order->execute();
  $order_id = $stmt_order->insert_id;
  $stmt_order->close();

  $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");

  foreach ($data as $item) {
    $product_id = $item['id'];
    $size = $item['size'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    $check = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    $result = $check->get_result();
    $product = $result->fetch_assoc();
    $check->close();

    if (!$product || $product['stock'] < $quantity) {
      throw new Exception("Insufficient stock for product ID $product_id");
    }

    $stmt_item->bind_param("iisid", $order_id, $product_id, $size, $quantity, $price);
    $stmt_item->execute();

    $update = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $update->bind_param("ii", $quantity, $product_id);
    $update->execute();
    $update->close();
  }

  $stmt_item->close();
  $conn->commit();
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  $conn->rollback();
  echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
