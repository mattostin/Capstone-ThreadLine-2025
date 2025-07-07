<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Debugging (uncomment if needed)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// DB Connection
$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Payment Logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_data'])) {
  $user_id = $_SESSION['user_id'];
  $cart = json_decode($_POST['cart_data'], true);

  if (!$cart || count($cart) === 0) {
    echo "<h3>Your cart is empty.</h3>";
    exit;
  }

  $order_sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
  $stmt = $conn->prepare($order_sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $order_id = $stmt->insert_id;
  $stmt->close();

  foreach ($cart as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $insert_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $insert_item->execute();
    $insert_item->close();

    $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
    $update_stock->bind_param("iii", $quantity, $product_id, $quantity);
    $update_stock->execute();
    $update_stock->close();
  }

  $conn->close();
  echo "<h2>✅ Payment successful! Order placed.</h2><script>localStorage.removeItem('cart');</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      background: linear-gradient(to bottom, #82b3e3, #075eb6);
      font-family: 'Poppins', sans-serif;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: #054a8e;
      color: white;
    }

    .navbar .logo {
      font-size: 1.5rem;
      font-weight: bold;
      text-decoration: none;
      color: white;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      gap: 1rem;
    }

    .navbar a {
      color: white;
      text-decoration: none;
    }

    .payment-container {
      max-width: 500px;
      margin: 5rem auto;
      padding: 2rem;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .payment-container h2 {
      margin-bottom: 1rem;
    }

    input[type="text"] {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 0.75rem;
      background-color: #075eb6;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 1rem;
    }

    button:hover {
      background-color: #054a8e;
    }
  </style>
</head>
<body>
  <!-- ✅ NAVBAR -->
  <header class="navbar">
    <a href="/php/logo_redirect.php" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li><a href="/php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li><strong>Hello, <?= htmlspecialchars($_SESSION['username']) ?></strong></li>
        <li><a href="/php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/php/login.php">Login</a></li>
        <li><a href="/php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <!-- ✅ FORM -->
  <div class="payment-container">
    <h2>Enter Payment Info</h2>
    <form method="POST" id="payment-form">
      <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="text" name="address" placeholder="Shipping Address" required>
      <input type="text" name="cardholder" placeholder="Cardholder Name" required>
      <input type="text" name="cardnumber" placeholder="Card Number" required>
      <input type="text" name="expiry" placeholder="MM/YY" required>
      <input type="text" name="cvv" placeholder="CVV" required>
      <input type="hidden" name="cart_data" id="cart_data_field">
      <button type="submit">Pay Now</button>
    </form>
  </div>

  <script>
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    document.getElementById('cart_data_field').value = JSON.stringify(cart);
  </script>
</body>
</html>
