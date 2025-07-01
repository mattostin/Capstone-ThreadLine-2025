<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ThreadLine | Home</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <nav class="navbar">
    <h1 class="logo">ThreadLine</h1>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="codeForBothJackets.html">Shop</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li style="color: white; font-weight: bold;">Hi, <?= htmlspecialchars($_SESSION['username']) ?></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- You can add your main homepage content here -->
  <section class="hero">
    <h1>Welcome to ThreadLine</h1>
    <p>Your source for style and comfort.</p>
    <a href="codeForBothJackets.html" class="shop-now-btn">Shop Now</a>
  </section>

  <footer>
    <p>© 2025 ThreadLine. All rights reserved.</p>
  </footer>
</body>
</html>
