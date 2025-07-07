<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Session timeout logic
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
    $stmt = $conn->prepare("UPDATE users SET last_activity = ? WHERE id = ?");
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
  <style>
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
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-right: 1rem;
    }

    #clear-cart-btn:hover {
      background-color: #c0392b;
    }

    #payment-btn {
      background-color: #075eb6;
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
</head>
<body>
  <header class="navbar">
    <a href="/php/logo_redirect.php" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li><a href="/php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
          <li><a href="/php/admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
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

        left.innerHTML = `<strong>${item.name}</strong><br>Size: ${item.size}<br>Qty: ${item.quantity}`;
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

      document.getElementById('payment-btn').addEventListener('click', async () => {
        const response = await fetch('payment.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(cart)
        });

        const result = await response.json();
        if (response.ok) {
          alert("✅ Payment successful!");
          localStorage.removeItem('cart');
          window.location.href = "thankyou.php";
        } else {
          alert("❌ " + result.error);
        }
      });
    });
  </script>
</body>
</html>
