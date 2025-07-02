<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop - ThreadLine</title>
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

  <main>
    <h1 style="text-align: center;">Our Featured Clothing</h1>
    <section class="product-grid">
      <!-- Product Box -->
      <div class="product-box" data-id="1" data-name="White Jacket" data-price="55">
        <div class="product-images bg-white">
          <img src="white-frontt.png" alt="White Jacket Front">
          <img src="white-back.png" alt="White Jacket Back">
        </div>
        <p>Men's Softness Sport Jacket - White<br>$55</p>
        <input type="number" min="1" value="1" class="qty-input">
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>

      <div class="product-box" data-id="2" data-name="Gray Jacket" data-price="55">
        <div class="product-images bg-gray">
          <img src="gray-front.png" alt="Gray Jacket Front">
          <img src="gray-back.png" alt="Gray Jacket Back">
        </div>
        <p>Men's Softness Sport Jacket - Gray<br>$55</p>
        <input type="number" min="1" value="1" class="qty-input">
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>

      <div class="product-box" data-id="3" data-name="Green Shorts" data-price="35">
        <div class="product-images bg-green">
          <img src="greenShortFront.png" alt="Green Shorts Front">
          <img src="greenShortBack.png" alt="Green Shorts Back">
        </div>
        <p>Men’s Everyday Shorts - Green<br>$35</p>
        <input type="number" min="1" value="1" class="qty-input">
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
    </section>
  </main>
</body>
</html>
