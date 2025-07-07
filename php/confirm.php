<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// 🚫 Require login
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// ✅ Sanitize & get form data
$fullname = htmlspecialchars($_POST['fullname'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$zip = htmlspecialchars($_POST['zip'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$cartJson = $_POST['cart'] ?? '[]';
$cartData = json_decode($cartJson, true);
$total = 0;

// ✅ Connect to DB
$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("DB Connection failed: " . $conn->connect_error);
}

// ✅ Reduce inventory stock
if (is_array($cartData)) {
  foreach ($cartData as $item) {
    $product_id = (int) $item['id'];
    $size = $conn->real_escape_string($item['size']);
    $qty = (int) $item['quantity'];
    $price = (float) $item['price'];
    $total += $qty * $price;

    $stmt = $conn->prepare("UPDATE inventory SET stock = stock - ? WHERE product_id = ? AND size = ?");
    $stmt->bind_param("iis", $qty, $product_id, $size);
    $stmt->execute();
    $stmt->close();
  }

  // ✅ Insert order record (optional)
  $username = $conn->real_escape_string($_SESSION['username']);
  $stmt = $conn->prepare("INSERT INTO orders (username, fullname, address, email, total, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param("ssssd", $username, $fullname, $address, $email, $total);
  $stmt->execute();
  $stmt->close();
} else {
  $cartData = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Lilita+One&display=swap" rel="stylesheet" />
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
      padding: 0.65rem 1.25rem;
      border-radius: 8px;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .confirmation-buttons a:hover {
      background-color: #054a8e;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="/php/logo_redirect.php" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
          <li><a href="/php/admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="/php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/php/login.php">Login</a></li>
        <li><a href="/php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <div class="confirmation-container">
    <h2>✅ Order Confirmed</h2>
    <p>Thank you, <strong><?= $fullname ?></strong>! Your order has been successfully placed and will be shipped to:</p>
    <p><?= $address ?><br><?= $zip ?></p>
    <h3>Order Summary:</h3>

    <?php
    if (!empty($cartData)) {
      echo "<ul>";
      foreach ($cartData as $item) {
        $name = htmlspecialchars($item['name']);
        $size = htmlspecialchars($item['size']);
        $qty  = (int)$item['quantity'];
        $price = (float)$item['price'];
        $subtotal = $qty * $price;
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
      <a href="/php/codeForBothJackets.php">Continue Shopping</a>
      <a href="/php/logout.php">Logout</a>
    </div>
  </div>

  <script>
    localStorage.removeItem("cart");
  </script>
</body>
</html>
