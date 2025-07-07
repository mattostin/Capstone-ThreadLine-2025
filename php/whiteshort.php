<?php
session_start();
$productId = 2; // White Shorts product ID
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>White Shorts - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    /* (no changes to your styles) */
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
        const existingIndex = cart.findIndex(item => item.id === 2 && item.size === selectedSize);

        if (existingIndex > -1) {
          cart[existingIndex].quantity += quantity;
        } else {
          cart.push({
            id: 2,
            name: "White Shorts",
            price: 35,
            quantity: quantity,
            size: selectedSize
          });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        alert("White Shorts added to cart!");
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="<?= isset($_SESSION['username']) ? '../php/home.php' : '../html/index.html' ?>" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="../php/codeForBothJackets.php">Shop</a></li>
      <li><a href="../php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
          <li><a href="../php/admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="../php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="../php/login.php">Login</a></li>
        <li><a href="../php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main>
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="../images/whiteShortFront.png" alt="White Shorts Front">
        <img src="../images/whiteShortBack.png" alt="White Shorts Back">
      </div>
      <p>Men's Soft Cotton Shorts - White</p>
      <strong>$35</strong>

      <form id="addToCartForm">
        <label style="font-size: 1.4rem;">Size:</label>
        <div class="size-selector">
          <button type="button" class="size-btn" data-size="S">S</button>
          <button type="button" class="size-btn" data-size="M">M</button>
          <button type="button" class="size-btn" data-size="L">L</button>
          <button type="button" class="size-btn" data-size="XL">XL</button>
        </div>

        <label for="quantity" style="font-size: 1.4rem;">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" />
        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </main>

  <!-- ✅ JS Tracker Logic -->
  <script>
    const startTime = Date.now();

    window.addEventListener("beforeunload", function () {
      const durationSeconds = Math.round((Date.now() - startTime) / 1000);
      const payload = {
        user_id: <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null' ?>,
        product_id: <?= $productId ?>,
        page: "White Shorts",
        duration_seconds: durationSeconds
      };

      navigator.sendBeacon("../php/track_view.php", JSON.stringify(payload));
    });
  </script>
</body>
</html>
