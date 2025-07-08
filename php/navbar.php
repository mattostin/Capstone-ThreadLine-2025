<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<nav class="navbar">
  <a class="logo" href="logo_redirect.php">ThreadLine</a>
  <ul class="nav-links">
    <li><a href="codeForBothJackets.php">Shop</a></li>
    <li><a href="checkout.php">Checkout</a></li>

    <?php if (isset($_SESSION['username'])): ?>
      <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
        <li><a href="admin-home.php">Admin Home</a></li>
        <li><a href="admin-dashboard.php">Dashboard</a></li>
        <li><a href="admin-product-crud.php">Manage Products</a></li>
      <?php endif; ?>
      <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="signup.php">Signup</a></li>
    <?php endif; ?>
  </ul>
</nav>
