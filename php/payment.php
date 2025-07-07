<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?redirect=payment.php");
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];
$cart = json_decode($_POST['cart'] ?? '[]', true);

if (empty($cart)) {
  die("Cart is empty.");
}

$conn->begin_transaction();

try {
  // Insert new order
  $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $orderId = $stmt->insert_id;
  $stmt->close();

  $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
  $updateStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_name = ? AND stock >= ?");

  foreach ($cart as $item) {
    $name = $item['name'];
    $size = $item['size'];
    $quantity = (int)$item['quantity'];
    $price = (float)$item['price'];

    $itemStmt->bind_param("issid", $orderId, $name, $size, $quantity, $price);
    $itemStmt->execute();

    // Update stock
    $updateStock->bind_param("isi", $quantity, $name, $quantity);
    $updateStock->execute();
  }

  $itemStmt->close();
  $updateStock->close();

  // Simulate successful payment
  $paymentStmt = $conn->prepare("INSERT INTO threadline_payments (user_id, order_id, amount, payment_date) VALUES (?, ?, ?, NOW())");
  $totalAmount = array_reduce($cart, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
  $paymentStmt->bind_param("iid", $userId, $orderId, $totalAmount);
  $paymentStmt->execute();
  $paymentStmt->close();

  $conn->commit();
  $conn->close();

  echo "<script>
    localStorage.removeItem('cart');
    alert('Payment successful! Your order has been placed.');
    window.location.href = 'codeForBothJackets.php';
  </script>";

} catch (Exception $e) {
  $conn->rollback();
  $conn->close();
  die("Payment failed: " . $e->getMessage());
}
?>
