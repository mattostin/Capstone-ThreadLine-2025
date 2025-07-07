<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Session timeout: 30 minutes
if (!isset($_SESSION['LAST_ACTIVITY'])) {
  $_SESSION['LAST_ACTIVITY'] = time();
} elseif (time() - $_SESSION['LAST_ACTIVITY'] > 1800) {
  session_unset();
  session_destroy();
  header("Location: login.php?redirect=checkout.php");
  exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// Update last_activity in the DB
if (isset($_SESSION['user_id'])) {
  date_default_timezone_set('America/Los_Angeles');
  $conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");

  if (!$conn->connect_error) {
    $now = date('Y-m-d H:i:s');
    $updateSql = "UPDATE users SET last_activity = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $now, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
  <style>
    /* (styling unchanged) */
  </style>

  <script>
    // (JavaScript unchanged)
  </script>
</head>
<body>

<?php
// ✅ Reusable Navbar with Dashboard logic
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
        <li><a href="admin-dashboard.php">Dashboard</a></li>
      <?php endif; ?>
      <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="signup.php">Signup</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main class="checkout-container">
  <h2>Checkout Summary</h2>
  <div id="checkout-items"></div>
  <h3 id="total-amount"></h3>
  <div class="checkout-actions">
    <button id="clear-cart-btn">Clear Cart</button>
    <button id="payment-btn">Proceed to Payment</button>
  </div>
</main>
</body>
</html>
