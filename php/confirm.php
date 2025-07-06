<?php
session_start();

// Session timeout: 30 minutes
if (!isset($_SESSION['LAST_ACTIVITY'])) {
  $_SESSION['LAST_ACTIVITY'] = time();
} elseif (time() - $_SESSION['LAST_ACTIVITY'] > 1800) {
  session_unset();
  session_destroy();
  header("Location: login.php?redirect=payment.php");
  exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve form data safely
$fullname = htmlspecialchars($_POST['fullname'] ?? '');
$address  = htmlspecialchars($_POST['address'] ?? '');
$email    = htmlspecialchars($_POST['email'] ?? '');
$card     = htmlspecialchars($_POST['card'] ?? '');
$expiryMonth = htmlspecialchars($_POST['expiryMonth'] ?? '');
$expiryYear  = htmlspecialchars($_POST['expiryYear'] ?? '');
$cvv      = htmlspecialchars($_POST['cvv'] ?? '');
$zip      = htmlspecialchars($_POST['zip'] ?? '');
$cartJSON = $_POST['cart'] ?? '[]';

$cartItems = json_decode($cartJSON, true);
$totalPrice = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .confirm-container {
      max-width: 900px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
    }

    h2 {
      margin-bottom: 1.5rem;
    }

    .order-summary {
      margin-top: 2rem;
    }

    .order-summary ul {
      list-style: none;
      padding: 0;
    }

    .order-summary li {
      margin-bottom: 1rem;
      padding: 1rem;
      background: #f7f7f7;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
    }

    .total {
      margin-top: 1.5rem;
      font-size: 1.2rem;
      font-weight: bold;
      text-align: right;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="../html/index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </header>

  <main class="confirm-container">
    <h2>✅ Thank You for Your Purchase, <?= htmlentities($fullname) ?>!</h2>
    <p>Your order has been successfully processed.</p>
    <p><strong>Shipping to:</strong> <?= htmlentities($address) ?> | <?= htmlentities($zip) ?></p>
    <p><strong>Email confirmation sent to:</strong> <?= htmlentities($email) ?></p>

    <div class="order-summary">
      <h3>Order Summary:</h3>
      <ul>
        <?php if (is_array($cartItems)) : ?>
          <?php foreach ($cartItems as $item): 
              $itemName = htmlentities($item['name']);
              $itemQty  = (int)$item['quantity'];
              $itemSize = htmlentities($item['size']);
              $itemPrice = (float)$item['price'];
              $lineTotal = $itemQty * $itemPrice;
              $totalPrice += $lineTotal;
          ?>
            <li>
              <span><?= "$itemName (Size: $itemSize, Qty: $itemQty)" ?></span>
              <span>$<?= number_format($lineTotal, 2) ?></span>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No items in cart.</li>
        <?php endif; ?>
      </ul>
      <div class="total">Total Paid: $<?= number_format($totalPrice, 2) ?></div>
    </div>
  </main>

  <script>
    // Clear cart from localStorage after confirmation
    localStorage.removeItem("cart");
  </script>
</body>
</html>
