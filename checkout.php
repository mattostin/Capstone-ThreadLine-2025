<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
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
        li.textContent = `${item.name} - $${item.price} x ${item.quantity}`;
        ul.appendChild(li);
        total += item.price * item.quantity;
      });

      container.appendChild(ul);
      document.getElementById('total-amount').textContent = "Total: $" + total;
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </header>

  <main class="checkout-container">
    <h2>Checkout Summary</h2>
    <div id="checkout-items"></div>
    <h3 id="total-amount"></h3>
  </main>
</body>
</html>
