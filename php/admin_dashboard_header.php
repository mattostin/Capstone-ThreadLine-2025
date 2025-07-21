<?php session_start(); ?>
<header style="background: white; padding: 1rem 2rem;">
  <div style="display: flex; justify-content: space-between; align-items: center;">
    <a href="/php/index.php" style="font-weight: bold; font-size: 1.5rem; color: #075eb6; text-decoration: none;">ThreadLine</a>

    <nav style="display: flex; gap: 1.5rem; align-items: center; position: relative;">
      <a href="/php/product-catalog.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Shop</a>
      <a href="/php/checkout.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Checkout</a>

      <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
        <a href="/php/index.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Admin Home</a>
        <a href="/php/admin-dashboard.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Dashboard</a>
        <a href="/php/admin-product-crud.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Manage Products</a>
      <?php endif; ?>

      <?php if (isset($_SESSION['username'])): ?>
        <span style="color: #075eb6; font-weight: bold;">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
        <a href="/php/logout.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Logout</a>
      <?php else: ?>
        <a href="/php/login.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
