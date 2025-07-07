<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("X-XSS-Protection: 1; mode=block");

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  die("<h2>❌ Invalid CSRF token. Please go back and try again.</h2>");
}

$required = ['fullname', 'address', 'email', 'card', 'expiryMonth', 'expiryYear', 'cvv', 'zip', 'cart'];
foreach ($required as $field) {
  if (empty($_POST[$field])) {
    die("<h2>❌ Missing field: $field. Please complete all required fields.</h2>");
  }
}

$fullname  = htmlspecialchars(trim($_POST['fullname']));
$address   = htmlspecialchars(trim($_POST['address']));
$email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$card      = preg_replace('/\D/', '', $_POST['card']);
$expiryMonth = $_POST['expiryMonth'];
$expiryYear  = $_POST['expiryYear'];
$cvv      = preg_replace('/\D/', '', $_POST['cvv']);
$zip      = htmlspecialchars(trim($_POST['zip']));
$cartData = json_decode($_POST['cart'], true);

if (strlen($card) < 13 || strlen($card) > 19) {
  die("<h2>❌ Invalid card number.</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Lilita+One&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #1071977a 0%, #88b9e9 50%, #075eb6 100%);
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      margin: 0;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: transparent;
    }

    .logo {
      font-family: 'Lilita One', cursive;
      font-size: 1.5rem;
      color: white;
      text-decoration: none;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }

    .nav-links li a, .nav-links li span {
      color: white;
      text-decoration: none;
      font-weight: 600;
      background: none !important;
      padding: 0.5rem 1rem;
      border-radius: 8px;
    }

    .nav-links li a:hover {
      text-decoration: underline;
    }

    .confirmation-container {
      max-width: 800px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      font-size: 2rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    ul {
      padding: 0;
      list-style: none;
    }

    li {
      margin-bottom: 1rem;
      background: #f3f3f3;
      padding: 1rem;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
    }

    .summary {
      font-size: 1.2rem;
      margin-top: 2rem;
      font-weight: bold;
      text-align: right;
    }

    .confirmation-buttons {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 2.5rem;
    }

    .confirmation-buttons a {
      text-decoration: none;
      background-color: #075eb6;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .confirmation-buttons a:hover {
      background-color: #054a8e;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="logo_redirect.php" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="checkout.php">Checkout</a></li>
      <?php
      if (isset($_SESSION['username'])) {
        $username = ucfirst(htmlspecialchars($_SESSION['username']));
        echo "<li><span>Hi, $username</span></li>";
        echo '<li><a href="logout.php">Logout</a></li>';
      } else {
        echo '<li><a href="login.php">Login</a></li>';
        echo '<li><a href="signup.php">Signup</a></li>';
      }
      ?>
    </ul>
  </header>

  <div class="confirmation-container">
    <h2>✅ Order Confirmed</h2>
    <p>Thank you, <strong><?= $fullname ?></strong>! Your order has been successfully placed and will be shipped to:</p>
    <p><?= $address ?><br><?= $zip ?></p>
    <h3>Order Summary:</h3>

    <?php
    $total = 0;
    if (is_array($cartData)) {
      echo "<ul>";
      foreach ($cartData as $item) {
        $name = htmlspecialchars($item['name']);
        $size = htmlspecialchars($item['size']);
        $qty  = (int)$item['quantity'];
        $price = (float)$item['price'];
        $subtotal = $qty * $price;
        $total += $subtotal;
        echo "<li><div>$name (Size: $size, Qty: $qty)</div><div>$" . number_format($subtotal, 2) . "</div></li>";
      }
      echo "</ul>";
      echo "<div class='summary'>Total Paid: $" . number_format($total, 2) . "</div>";
    } else {
      echo "<p>❌ Error reading cart.</p>";
    }
    ?>

    <p style="margin-top: 2rem;">A confirmation email has been sent to <strong><?= $email ?></strong>.</p>

    <div class="confirmation-buttons">
      <a href="codeForBothJackets.php">Continue Shopping</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <script>
    localStorage.removeItem("cart");
  </script>
</body>
</html>
