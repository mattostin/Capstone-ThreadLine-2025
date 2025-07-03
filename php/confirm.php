<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("❌ You must be logged in to confirm a payment.");
}

// Get user-submitted info
$user_id      = $_SESSION['user_id'];
$fullname     = $_POST['fullname'];
$address      = $_POST['address'];
$email        = $_POST['email'];
$card         = $_POST['card'];
$expiryMonth  = $_POST['expiryMonth'];
$expiryYear   = $_POST['expiryYear'];
$cvv          = $_POST['cvv'];
$zip          = $_POST['zip'];
$order_date   = date('Y-m-d H:i:s');

// Decode the cart from cookie
$cart_items = [];
if (isset($_COOKIE['cart'])) {
    $cart_items = json_decode($_COOKIE['cart'], true);
}

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, email, card_number, exp_month, exp_year, cvv, zip, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssis", $user_id, $fullname, $address, $email, $card, $expiryMonth, $expiryYear, $cvv, $zip, $order_date);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each product into order_items
if (!empty($cart_items)) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $name = $item['name'];
        $size = $item['size'];
        $qty  = $item['quantity'];
        $price = $item['price'];
        $stmt->bind_param("issid", $order_id, $name, $size, $qty, $price);
        $stmt->execute();
    }
    $stmt->close();
}

// Clear cart cookie
setcookie('cart', '', time() - 3600, '/');

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmed - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .confirmation-container {
      max-width: 700px;
      margin: 4rem auto;
      padding: 2.5rem;
      background-color: #ffffffee;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
      text-align: center;
    }

    h1 {
      color: #075eb6;
      margin-bottom: 1.5rem;
    }

    p {
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    .button {
      margin-top: 2rem;
      display: inline-block;
      background-color: #075eb6;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }

    .button:hover {
      background-color: #054a8e;
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
