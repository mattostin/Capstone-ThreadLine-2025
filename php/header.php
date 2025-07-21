<?php session_start(); ?>
<header style="background: white; padding: 1rem 2rem;">
  <div style="display: flex; justify-content: space-between; align-items: center;">
    <a href="/php/index.php" style="font-weight: bold; font-size: 1.5rem; color: #075eb6; text-decoration: none;">ThreadLine</a>

    <nav style="display: flex; gap: 1.5rem; align-items: center; position: relative;">
      <a href="/php/index.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Home</a>
      <a href="/php/product-catalog.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">Shop</a>
      <a href="/php/checkout.php" style="color: #075eb6; text-decoration: none; font-weight: bold;">View Cart</a>

      <div class="account-dropdown" style="position: relative;">
        <a href="javascript:void(0);" class="account-toggle" style="color: #075eb6; text-decoration: none; font-weight: bold;">
          <i class="fa fa-user"></i>
          <?= isset($_SESSION['username']) ? 'Hi, ' . htmlspecialchars($_SESSION['username']) : 'My Account' ?>
          <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-style" style="display: none; position: absolute; top: 100%; right: 0; background: white; list-style: none; padding: 1rem; margin: 0; box-shadow: 0 0 8px rgba(0,0,0,0.1); border-radius: 8px; min-width: 180px;">
          <?php if (isset($_SESSION['username'])): ?>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
              <li><a href="/php/admin_dashboard.php" style="display: block; padding: 0.5rem 1rem; color: #075eb6; text-decoration: none; font-weight: bold;">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="/php/profile.php" style="display: block; padding: 0.5rem 1rem; color: #075eb6; text-decoration: none; font-weight: bold;">Edit Profile</a></li>
            <li><a href="/php/logout.php" style="display: block; padding: 0.5rem 1rem; color: #075eb6; text-decoration: none; font-weight: bold;">Logout</a></li>
          <?php else: ?>
            <li><a href="/php/login.php" style="display: block; padding: 0.5rem 1rem; color: #075eb6; text-decoration: none; font-weight: bold;">Login/Register</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const toggle = document.querySelector(".account-toggle");
      const menu = document.querySelector(".dropdown-style");

      if (toggle && menu) {
        toggle.addEventListener("click", () => {
          menu.style.display = (menu.style.display === "block") ? "none" : "block";
        });

        document.addEventListener("click", function (e) {
          if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
          }
        });
      }
    });
  </script>
</header>
