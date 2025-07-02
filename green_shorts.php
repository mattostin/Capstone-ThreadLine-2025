<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Green Shorts - ThreadLine</title>
  <link rel="stylesheet" href="style.css">
  <script src="cart.js" defer></script>
</head>
<body>
  <header class="navbar">
    <a href="index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main class="product-detail-container">
    <div class="product-detail">
      <img src="greenShortFront.png" alt="Green Shorts Front" class="product-image">
      <h2>Men’s Everyday Shorts - Green</h2>
      <p>$35</p>
      <label for="size">Size:</label>
      <select id="size-green" class="size-select">
        <option value="S">Small</option>
        <option value="M">Medium</option>
        <option value="L">Large</option>
        <option value="XL">XL</option>
      </select>
      <br>
      <label for="quantity">Quantity:</label>
      <input type="number" id="qty-green" class="qty-input" value="1" min="1">
      <br>
      <button class="add-to-cart-btn" onclick="addToCart(3, 'Green Shorts', 35, 'greenShortFront.png', 'size-green', 'qty-green')">Add to Cart</button>
    </div>
  </main>
</body>
</html>
