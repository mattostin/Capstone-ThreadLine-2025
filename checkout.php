<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .checkout-container {
      max-width: 1000px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffee;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .checkout-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem;
      border-bottom: 1px solid #ccc;
    }
    .checkout-item img {
      width: 60px;
      height: 60px;
      object-fit: contain;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-right: 1rem;
    }
    .checkout-item .item-info {
      display: flex;
      align-items: center;
      flex: 1;
    }
    .checkout-item .item-name {
      font-size: 1rem;
      font-weight: bold;
      color: #222;
    }
    #total-amount {
      text-align: right;
      font-size: 1.4rem;
      margin-top: 2rem;
      font-weight: bold;
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

      cart.forEach(item => {
        const div = document.createElement('div');
        div.className = 'checkout-item';

        const image = document.createElement('img');
        if (item.name.includes("White Jacket")) {
          image.src = 'white-frontt.png';
        } else if (item.name.includes("Gray Jacket")) {
          image.src = 'gray-front.png';
        } else if (item.name.includes("Green Shorts")) {
          image.src = 'greenShortFront.png';
        } else {
          image.src = 'default.png'; // fallback
        }

        const info = document.createElement('div');
        info.className = 'item-info';

        const name = document.createElement('span');
        name.className = 'item-name';
        name.textContent = `${item.name} - $${item.price} x ${item.quantity}`;

        info.appendChild(image);
        info.appendChild(name);
        div.appendChild(info);
        container.appendChild(div);

        total += item.price * item.quantity;
      });

      document.getElementById('total-amount').textContent = "Total: $" + total.toFixed(2);
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
