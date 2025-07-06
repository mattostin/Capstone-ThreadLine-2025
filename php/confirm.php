<?php
// Secure session settings
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Basic headers for protection
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("X-XSS-Protection: 1; mode=block");

// CSRF token validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  die("<h2>❌ Invalid CSRF token. Please go back and try again.</h2>");
}

// Validate presence of required fields
$required = ['fullname', 'address', 'email', 'card', 'expiryMonth', 'expiryYear', 'cvv', 'zip', 'cart'];
foreach ($required as $field) {
  if (empty($_POST[$field])) {
    die("<h2>❌ Missing field: $field. Please complete all required fields.</h2>");
  }
}

// Sanitize inputs
$fullname  = htmlspecialchars(trim($_POST['fullname']));
$address   = htmlspecialchars(trim($_POST['address']));
$email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$card      = preg_replace('/\D/', '', $_POST['card']);
$expiryMonth = $_POST['expiryMonth'];
$expiryYear  = $_POST['expiryYear'];
$cvv      = preg_replace('/\D/', '', $_POST['cvv']);
$zip      = htmlspecialchars(trim($_POST['zip']));
$cartData = json_decode($_POST['cart'], true);

// Validate card number (basic check length)
if (strlen($card) < 13 || strlen($card) > 19) {
  die("<h2>❌ Invalid card number.</h2>");
}

// Confirm page
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .confirmation-container {
      max-width: 800px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
    }
    h2 {
      font-size: 2rem;
      margin-bottom: 1.5rem;
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
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: #075eb6;
    }
    .navbar .logo {
      font-family: 'Lilita One', cursive;
      font-size: 1.5rem;
      color: white;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="";
HTML;

if (isset($_SESSION['username'])) {
  echo 'codeForBothJackets.php';
} else {
  echo '../html/index.html';
}

echo <<<HTML
" class="logo">ThreadLine</a>
  </header>

  <div class="confirmation-container">
    <h2>✅ Order Confirmed</h2>
    <p>Thank you, <strong>$fullname</strong>! Your order has been successfully placed and will be shipped to:</p>
    <p>$address<br>$zip</p>
    <h3>Order Summary:</h3>
HTML;

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
    echo "<li><div>$name (Size: $size, Qty: $qty)</div><div>\$" . number_format($subtotal, 2) . "</div></li>";
  }
  echo "</ul>";
  echo "<div class='summary'>Total Paid: \$" . number_format($total, 2) . "</div>";
} else {
  echo "<p>❌ Error reading cart.</p>";
}

echo <<<HTML
    <p style="margin-top: 2rem;">A confirmation email has been sent to <strong>$email</strong>.</p>
    
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
HTML;
?>
