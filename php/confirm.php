<?php
session_start();
require 'config.php';

$fullname = $_POST['fullname'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$card = $_POST['card'] ?? '';
$expiryMonth = $_POST['expiryMonth'] ?? '';
$expiryYear = $_POST['expiryYear'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$zip = $_POST['zip'] ?? '';
$cart_json = $_POST['cart'] ?? '[]';

// Save to `orders` table
$card_last4 = substr(preg_replace('/\D/', '', $card), -4); // just digits, last 4
$stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, email, card_last4, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->execute([$_SESSION['user_id'] ?? 0, $fullname, $address, $email, $card_last4]);
$order_id = $conn->lastInsertId();

// Save to `order_items` table
$cart_items = json_decode($cart_json, true);

if (is_array($cart_items)) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $stmt->execute([
            $order_id,
            $item['name'] ?? '',
            $item['size'] ?? '',
            $item['quantity'] ?? 1,
            $item['price'] ?? 0
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .confirmation-container {
      max-width: 700px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
      text-align: center;
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    p {
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    a.button {
      display: inline-block;
      margin-top: 1.5rem;
      padding: 0.75rem 1.5rem;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    a.button:hover {
      background-color: #0056b3;
    }
  </style>
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
