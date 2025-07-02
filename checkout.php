<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .checkout-container {
      max-width: 900px;
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
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .checkout-item-img {
      width: 70px;
      height: 70px;
      object-fit: contain;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-right: 1rem;
    }

    .checkout-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    #clear-cart-btn {
      margin-top: 2rem;
      padding: 0.75rem 1.5rem;
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    #clear-cart-btn:hover {
      background-color: #c0392b;
    }

    #total-amount {
      font-size: 1.2rem;
      margin-top: 1rem;
      text-align: right;
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

      const ul = document.createElement('ul');

      cart.forEach(item => {
        const li = document.createElement('li');
        const left = document.createElement('div');
        left.className = 'checkout-left';

        const img = document.createElement('img');
        img.className = 'checkout-item-img';
        img.src = item.image;
        img.alt = item.name;

        const nameQty = document.createElement('div');
        nameQty.innerHTML = `<strong>${item.name}</strong><br>Size: ${item.size}<br>Qty: ${item.quantity}`;

        left.appendChild(img);
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
    <button id="clear-cart-btn">Clear Cart</button>
  </main>
</body>
</html>
