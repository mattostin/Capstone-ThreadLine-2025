<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop - ThreadLine</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Lilita+One&display=swap" rel="stylesheet" />
</head>
<body>
  <header class="navbar">
    <a href="index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="index.html">Home</a></li>
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="checkout.html">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li style="color: white; font-weight: bold;">
          Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?>
        </li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main>
    <h1 style="text-align: center;">Our Featured Clothing</h1>

    <section class="product-grid">
      <!-- White Jacket -->
      <div class="product-box">
        <div class="product-images bg-white">
          <img src="white-frontt.png" alt="ThreadLine White Jacket Front">
          <img src="white-back.png" alt="ThreadLine White Jacket Back">
        </div>
        <p>Men's Softness Sport Jacket - White<br>$55</p>
        <a href="checkout.html" class="add-to-cart-btn">Add to Cart</a>
      </div>

      <!-- Gray Jacket -->
      <div class="product-box">
        <div class="product-images bg-gray">
          <img src="gray-front.png" alt="ThreadLine Gray Jacket Front">
          <img src="gray-back.png" alt="ThreadLine Gray Jacket Back">
        </div>
        <p>Men's Softness Sport Jacket - Gray<br>$55</p>
        <a href="checkout.html" class="add-to-cart-btn">Add to Cart</a>
      </div>

      <!-- Green Shorts -->
      <div class="product-box">
        <div class="product-images bg-green">
          <img src="greenShortFront.png" alt="ThreadLine Green Shorts Front">
          <img src="greenShortBack.png" alt="ThreadLine Green Shorts Back">
        </div>
        <p>Men’s Everyday Shorts - Green<br>$35</p>
        <a href="checkout.html" class="add-to-cart-btn">Add to Cart</a>
      </div>
    </section>
  </main>
</body>
</html>
