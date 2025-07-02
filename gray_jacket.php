<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gray Jacket - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
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

  <main class="product-detail">
    <h1>Men's Softness Sport Jacket - Gray</h1>
    <div class="product-detail-box">
      <img src="gray-front.png" alt="Gray Jacket Front" style="max-width: 300px;" />
      <img src="gray-back.png" alt="Gray Jacket Back" style="max-width: 300px;" />

      <div class="product-options" data-id="2" data-name="Gray Jacket" data-price="55">
        <p>Price: $55</p>
        <label for="size">Size:</label>
        <select id="size" class="size-select">
          <option value="S">S</option>
          <option value="M">M</option>
          <option value="L">L</option>
          <option value="XL">XL</option>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" class="qty-input" value="1" min="1" />

        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
    </div>
  </main>
</body>
</html>
