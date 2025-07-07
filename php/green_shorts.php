<?php
session_start();
$productId = 4; // Green Shorts product ID
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Green Shorts - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    /* Leave all your existing styles untouched */
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
        const existingIndex = cart.findIndex(item => item.id === 4 && item.size === selectedSize);

        if (existingIndex > -1) {
          cart[existingIndex].quantity += quantity;
        } else {
          cart.push({
            id: 4,
            name: "Green Shorts",
            price: 35,
            quantity: quantity,
            size: selectedSize
          });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        alert("Green Shorts added to cart!");
      });

      // ✅ Modern Analytics Tracking
      const sessionStart = Date.now();
      const productId = 4;
      const pageVisited = "Green Shorts";
      const userId = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;

      window.addEventListener("beforeunload", () => {
        const sessionEnd = Date.now();
        const durationSeconds = Math.round((sessionEnd - sessionStart) / 1000);

        navigator.sendBeacon("track_view.php", JSON.stringify({
          user_id: userId,
          product_id: productId,
          page_visited: pageVisited,
          session_start: sessionStart,
          session_end: sessionEnd,
          duration_seconds: durationSeconds
        }));
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="logo_redirect.php" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
          <li><a href="admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main class="product-fullscreen">
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="../images/greenShortFront.png" alt="Green Shorts Front">
        <img src="../images/greenShortBack.png" alt="Green Shorts Back">
      </div>
      <p>Men’s Everyday Shorts - Green</p>
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
        <input type="number" id="quantity" name="quantity" value="1" min="1" required />

        <br><br>
        <button type="submit" style="padding: 0.7rem 1.5rem; background: #075eb6; color: white; border: none; border-radius: 6px; font-weight: bold;">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
