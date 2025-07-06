<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /html/index.html");
    exit;
}
$username = ucfirst(htmlspecialchars($_SESSION['username']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
  <nav class="navbar">
    <a class="logo" href="/php/logo_redirect.php">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/checkout.php">Checkout</a></li>
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li style="color: white; font-weight: bold;">Hi, <?= $username ?></li>
      <li><a href="/php/logout.php">Logout</a></li>
    </ul>
  </nav>

  <main style="text-align: center; margin-top: 5rem;">
    <h1>Welcome back, <?= $username ?>! 👋</h1>
    <p>Ready to shop the latest styles? Browse our featured products below:</p>
    <a href="/php/codeForBothJackets.php" style="display: inline-block; margin-top: 2rem; padding: 1rem 2rem; background: #075eb6; color: white; border-radius: 8px; text-decoration: none;">Go to Shop</a>
  </main>
</body>
</html>
