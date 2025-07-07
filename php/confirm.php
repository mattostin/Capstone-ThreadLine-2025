<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

$fullname = htmlspecialchars($_POST['fullname'] ?? "Guest");
$address  = htmlspecialchars($_POST['address'] ?? "N/A");
$zip      = htmlspecialchars($_POST['zip'] ?? "00000");
$email    = htmlspecialchars($_POST['email'] ?? "noemail@example.com");
$cartData = json_decode($_POST['cart'] ?? '[]', true);

// DB connection
$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check stock
$outOfStockItems = [];
foreach ($cartData as $item) {
  $productName = $item['name'];
  $quantity = (int)$item['quantity'];

  $stmt = $conn->prepare("SELECT stock FROM products WHERE product_name = ?");
  $stmt->bind_param("s", $productName);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();
  $stmt->close();

  if (!$product || $product['stock'] < $quantity) {
    $outOfStockItems[] = $productName;
  }
}

if (!empty($outOfStockItems)) {
  echo "<h2 style='text-align:center;margin-top:4rem;font-family:Poppins,sans-serif;color:red;'>❌ Out of Stock</h2>";
  echo "<p style='text-align:center;font-family:Poppins,sans-serif;'>These item(s) are not available in that quantity:</p><ul style='text-align:center;font-family:Poppins,sans-serif;list-style:none;'>";
  foreach ($outOfStockItems as $item) {
    echo "<li><strong>$item</strong></li>";
  }
  echo "</ul><p style='text-align:center;font-family:Poppins,sans-serif;'><a href='/php/codeForBothJackets.php'>← Back to Shop</a></p>";
  exit;
}

// Deduct stock
foreach ($cartData as $item) {
  $productName = $item['name'];
  $quantity = (int)$item['quantity'];

  $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_name = ?");
  $stmt->bind_param("is", $quantity, $productName);
  $stmt->execute();
  $stmt->close();
}

// Calculate total
$total = 0;
foreach ($cartData as $item) {
  $total += $item['price'] * $item['quantity'];
}

// Insert into orders table
$user_id = $_SESSION['user_id'] ?? null;
$now = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, email, total, created_at, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssdss", $user_id, $fullname, $address, $email, $total, $now, $now);
$stmt->execute();
$stmt->close();

// Track guest checkout if no user_id
if (!$user_id) {
  $session_id = session_id();
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $checkout_time = $now;

  $insert = $conn->prepare("INSERT INTO guest_checkouts (session_id, ip_address, full_name, email, zip_code, checkout_time) VALUES (?, ?, ?, ?, ?, ?)");
  $insert->bind_param("ssssss", $session_id, $ip_address, $fullname, $email, $zip, $checkout_time);
  $insert->execute();
  $insert->close();
}
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
    }
    ul {
      padding: 0;
      list-style: none;
    }
    li {
      margin-bottom: 1rem;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      border-radius: 8px;
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
      <li><a href="/php/logout.php">Logout</a></li>
    </ul>
  </header>

  <div class="confirmation-container">
    <h2>✅ Order Confirmed</h2>
    <p>Thank you, <strong><?= $fullname ?></strong>! Your order will be shipped to:</p>
    <p><?= $address ?><br><?= $zip ?></p>
    <h3>Order Summary:</h3>
    <ul>
      <?php foreach ($cartData as $item): 
        $name = htmlspecialchars($item['name']);
        $qty  = (int)$item['quantity'];
        $price = (float)$item['price'];
        $subtotal = $qty * $price;
      ?>
        <li><div><?= $name ?> (Qty: <?= $qty ?>)</div><div>$<?= number_format($subtotal, 2) ?></div></li>
      <?php endforeach; ?>
    </ul>
    <div class="summary">Total Paid: $<?= number_format($total, 2) ?></div>
    <p style="margin-top: 2rem;">A confirmation email has been sent to <strong><?= $email ?></strong>.</p>
    <div class="confirmation-buttons">
      <a href="/php/codeForBothJackets.php">Continue Shopping</a>
      <a href="/php/logout.php">Logout</a>
    </div>
  </div>

  <script>localStorage.removeItem("cart");</script>
</body>
</html>
