<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header class="navbar">
    <a href="/html/index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
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

  <main>
    <h1 style="text-align: center;">Our Featured Clothing</h1>
    <section class="product-grid">
      <!-- White Jacket -->
      <a href="/php/white_jacket.php" class="product-box-link">
        <div class="product-box">
          <div class="product-images bg-white">
            <img src="/images/white-frontt.png" alt="White Jacket Front">
            <img src="/images/white-back.png" alt="White Jacket Back">
          </div>
          <p>Men's Softness Sport Jacket - White<br>$55</p>
        </div>
      </a>

      <!-- Gray Jacket -->
      <a href="/php/gray_jacket.php" class="product-box-link">
        <div class="product-box">
          <div class="product-images bg-gray">
            <img src="/images/gray-front.png" alt="Gray Jacket Front">
            <img src="/images/gray-back.png" alt="Gray Jacket Back">
          </div>
          <p>Men's Softness Sport Jacket - Gray<br>$55</p>
        </div>
      </a>

      <!-- White Shorts -->
      <a href="/php/whiteshort.php" class="product-box-link">
        <div class="product-box">
          <div class="product-images bg-white">
            <img src="/images/whiteShortFront.png" alt="White Short Front">
            <img src="/images/whiteShortBack.png" alt="White Short Back">
          </div>
          <p>Men's Softness Shorts - White<br>$40</p>
        </div>
      </a>

      <!-- Green Shorts -->
      <a href="/php/green_shorts.php" class="product-box-link">
        <div class="product-box">
          <div class="product-images bg-green">
            <img src="/images/greenShortFront.png" alt="Green Shorts Front">
            <img src="/images/greenShortBack.png" alt="Green Shorts Back">
          </div>
          <p>Men’s Everyday Shorts - Green<br>$35</p>
        </div>
      </a>
    </section>
  </main>
</body>
</html>
