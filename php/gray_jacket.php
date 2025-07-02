<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Gray Jacket - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
  <header class="navbar">
    <a href="/html/index.html" class="logo">ThreadLine</a>
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
        <img src="/images/gray-front.png" alt="Gray Jacket Front">
        <img src="/images/gray-back.png" alt="Gray Jacket Back">
      </div>
      <p>Men's Softness Sport Jacket - Gray</p>
      <strong>$55</strong>

      <form id="addToCartForm">
        <label style="font-size: 1.4rem;">Size:</label>
        <div class="size-selector">
          <button type="button" class="size-btn" data-size="S">S</button>
          <button type="button" class="size-btn" data-size="M">M</button>
          <button type="button" class="size-btn" data-size="L">L</button>
          <button type="button" class="size-btn" data-size="XL">XL</button>
        </div>

        <label for="quantity" style="font-size: 1.4rem;">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required />

        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
