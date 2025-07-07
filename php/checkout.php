<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Session timeout: 30 minutes
if (!isset($_SESSION['LAST_ACTIVITY'])) {
  $_SESSION['LAST_ACTIVITY'] = time();
} elseif (time() - $_SESSION['LAST_ACTIVITY'] > 1800) {
  session_unset();
  session_destroy();
  header("Location: login.php?redirect=checkout.php");
  exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// Update last_activity in DB
if (isset($_SESSION['user_id'])) {
  date_default_timezone_set('America/Los_Angeles');
  $conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");

  if (!$conn->connect_error) {
    $now = date('Y-m-d H:i:s');
    $updateSql = "UPDATE users SET last_activity = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $now, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #1071977a 0%, #88b9e9 50%, #075eb6 100%);
      min-height: 100vh;
      margin: 0;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: #075eb6;
    }

    .logo {
      font-family: 'Lilita One', cursive;
      font-size: 1.5rem;
      color: white;
      text-decoration: none;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }

    .nav-links li a, .nav-links li span {
      color: white;
      text-decoration: none;
      font-weight: 600;
    }

    .nav-links li a:hover {
      text-decoration: underline;
    }

    .checkout-container {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    #checkout-items ul {
      list-style: none;
      padding: 0;
    }

    #checkout-items li {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.2rem;
      background-color: #f9f9f9;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      font-size: 1.1rem;
    }

    .checkout-left {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }

    #clear-cart-btn, #payment-btn {
      margin-top: 2rem;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    #clear-cart-btn {
      background-color: #e74c3c;
      color: white;
      margin-right: 1rem;
    }

    #clear-cart-btn:hover {
      background-color: #c0392b;
    }

    #payment-btn {
      background-color: #075eb6;
      color: white;
    }

    #payment-btn:hover {
      background-color: #054a8e;
    }

    #total-amount {
      font-size: 1.4rem;
      margin-top: 2rem;
      text-align: right;
      font-weight: bold;
    }

    .checkout-actions {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      gap: 1rem;
      margin-top: 2rem;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const container = document.getElementById('checkout-items');
      let total = 0;

      if (cart.length === 0) {
        container.innerHTML = "<p>Your cart is empty.</p>";
        return;
      }

      const ul = document.createElement('ul');

      cart.forEach(item => {
        const li = document.createElement('li');
        const left = document.createElement('div');
        left.className = 'checkout-left';

        const nameQty = document.createElement('div');
        nameQty.innerHTML = `<strong>${item.name}</strong><br>Size: ${item.size}<br>Qty: ${item.quantity}`;
        left.appendChild(nameQty);

        const right = document.createElement('div');
        right.textContent = `$${(item.price * item.quantity).toFixed(2)}`;

        li.appendChild(left);
        li.appendChild(right);
        ul.appendChild(li);

        total += item.price * item.quantity;
      });

      container.appendChild(ul);
      document.getElementById('total-amount').textContent = "Total: $" + total.toFixed(2);

      document.getElementById('clear-cart-btn').addEventListener('click', () => {
        localStorage.removeItem('cart');
        location.reload();
      });

      document.getElementById('payment-btn').addEventListener('click', () => {
        window.location.href = 'payment.php';
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="/php/logo_redirect.php" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li><a href="/php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li><span>Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></span></li>
        <li><a href="/php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/php/login.php">Login</a></li>
        <li><a href="/php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main class="checkout-container">
    <h2>Checkout Summary</h2>
    <div id="checkout-items"></div>
    <h3 id="total-amount"></h3>
    <div class="checkout-actions">
      <button id="clear-cart-btn">Clear Cart</button>
      <button id="payment-btn">Proceed to Payment</button>
    </div>
  </main>
</body>
</html>
