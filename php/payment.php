<?php
session_start();
$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
  die("You must be logged in to make a payment.");
}

$user_id = $_SESSION['user_id'];
$cart = json_decode($_COOKIE['cart'] ?? '[]', true);

if (empty($cart)) {
  die("Your cart is empty.");
}

// Begin transaction
$conn->begin_transaction();

try {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  // Insert into payments table
  $stmt = $conn->prepare("INSERT INTO threadline_payments (user_id, total_amount, payment_status, created_at) VALUES (?, ?, 'Completed', NOW())");
  $stmt->bind_param("id", $user_id, $total);
  $stmt->execute();
  $payment_id = $stmt->insert_id;
  $stmt->close();

  // Insert into orders table
  $stmt = $conn->prepare("INSERT INTO orders (user_id, payment_id, created_at) VALUES (?, ?, NOW())");
  $stmt->bind_param("ii", $user_id, $payment_id);
  $stmt->execute();
  $order_id = $stmt->insert_id;
  $stmt->close();

  // Insert into order_items and update stock
  $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
  $stmtStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE name = ?");

  foreach ($cart as $item) {
    $stmtItem->bind_param("issid", $order_id, $item['name'], $item['size'], $item['quantity'], $item['price']);
    $stmtItem->execute();

    $stmtStock->bind_param("is", $item['quantity'], $item['name']);
    $stmtStock->execute();
  }

  $stmtItem->close();
  $stmtStock->close();

  $conn->commit();

  // Clear cart cookie
  setcookie("cart", "", time() - 3600, "/");

  echo "<h2>Payment Successful</h2>";
  echo "<p>Your order has been placed. Thank you!</p>";
  echo "<a href='codeForBothJackets.php'>Back to Shop</a>";

} catch (Exception $e) {
  $conn->rollback();
  echo "Payment failed: " . $e->getMessage();
}

$conn->close();
?>
