<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id     = $_SESSION['user_id'];
  $fullname    = $_POST['fullname'];
  $address     = $_POST['address'];
  $email       = $_POST['email'];
  $card        = $_POST['card'];
  $card_last4  = substr($card, -4);
  $expiryMonth = $_POST['expiryMonth'];
  $expiryYear  = $_POST['expiryYear'];
  $cvv         = $_POST['cvv'];
  $zip         = $_POST['zip'];

  // Insert into orders table
  $stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, email, card_last4) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $user_id, $fullname, $address, $email, $card_last4);
  $stmt->execute();
  $order_id = $stmt->insert_id;
  $stmt->close();

  // Decode cart items sent from JS
  $items = json_decode($_POST['cart'], true);

  // Insert each item into order_items table
  $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
  foreach ($items as $item) {
    $stmt->bind_param("issid", $order_id, $item['name'], $item['size'], $item['quantity'], $item['price']);
    $stmt->execute();
  }
  $stmt->close();

} else {
  header("Location: payment.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
  <header class="navbar">
    <a href="../html/index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="order_history.php">My Orders</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </header>

  <main class="confirmation-container">
    <h1>✅ Thank You, <?= htmlspecialchars($fullname) ?>!</h1>
    <p>Your order has been placed successfully.</p>
    <p>A confirmation email will be sent to <strong><?= htmlspecialchars($email) ?></strong>.</p>
    <p>Your order number is <strong>#<?= $order_id ?></strong></p>
    <a class="button" href="order_history.php">View My Orders</a>
  </main>
</body>
</html>
