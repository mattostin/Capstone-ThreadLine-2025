<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>White Jacket - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
  <style>
    /* [CSS remains unchanged] */
    .product-fullscreen { ... }
    .product-detail-box { ... }
    .product-detail-images img { ... }
    .product-detail-images { ... }
    .size-selector { ... }
    .size-btn { ... }
    .size-btn.selected { ... }
    form label { ... }
    input[type="number"] { ... }
    button[type="submit"] { ... }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let selectedSize = "";

      document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
          btn.classList.add('selected');
          selectedSize = btn.dataset.size;
        });
      });

      document.getElementById('addToCartForm').addEventListener('submit', e => {
        e.preventDefault();
        const quantity = parseInt(document.getElementById('quantity').value);

        if (!selectedSize) {
          alert("Please select a size.");
          return;
        }

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingIndex = cart.findIndex(item => item.id === 1 && item.size === selectedSize);

        if (existingIndex > -1) {
          cart[existingIndex].quantity += quantity;
        } else {
          cart.push({
            id: 1,
            name: "White Jacket",
            price: 55,
            quantity: quantity,
            size: selectedSize
          });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        alert("White Jacket added to cart!");
      });
    });

    // ⏱️ Analytics tracker
    const startTime = Date.now();
    window.addEventListener("beforeunload", () => {
      const duration = Math.round((Date.now() - startTime) / 1000);

      fetch("/php/track_view.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          product_id: 1,
          duration: duration
        })
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="<?= isset($_SESSION['username']) ? '/php/home.php' : '/html/index.html' ?>" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li><a href="/php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="/php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/php/login.php">Login</a></li>
        <li><a href="/php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main class="product-fullscreen">
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="/images/white-frontt.png" alt="White Jacket Front">
        <img src="/images/white-back.png" alt="White Jacket Back">
      </div>
      <p>Men's Softness Sport Jacket - White</p>
      <strong>$55</strong>

      <form id="addToCartForm">
        <label style="font-size: 1.4rem;">Size:</label>
        <div class="size-selector">
          <button type="button" class="size-btn" data-size="S">S</button>
          <button type="button" class="size-btn" data-size="M">M</button>
          <button type="button" class="size-btn" data-size="L">L</button>
          <button type="button" class="size-btn" data-size="XL">XL</button>
        </div>

        <label for="quantity" style="font-size: 1.4rem;">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" required>

        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
