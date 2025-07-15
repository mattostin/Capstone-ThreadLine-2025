<?php
date_default_timezone_set('America/Los_Angeles');

session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Sanitize input
$fullname = htmlspecialchars($_POST['fullname'] ?? "Guest");
$address  = htmlspecialchars($_POST['address'] ?? "N/A");
$zip      = htmlspecialchars($_POST['zip'] ?? "00000");
$email    = htmlspecialchars($_POST['email'] ?? "noemail@example.com");

$card = $_POST['card'] ?? "0000";
$cardLast4 = substr(preg_replace('/\D/', '', $card), -4);

$expMonth = htmlspecialchars($_POST['expiryMonth'] ?? "01");
$expYear  = htmlspecialchars($_POST['expiryYear'] ?? "1970");
$cvv      = htmlspecialchars($_POST['cvv'] ?? "000");

$cartData = json_decode($_POST['cart'] ?? '[]', true);

// DB connection
$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ✅ Check stock by product ID
$outOfStockItems = [];
foreach ($cartData as $item) {
  $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
  $stmt->bind_param("i", $item['id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();
  if (!$product || $product['stock'] < (int)$item['quantity']) {
    $outOfStockItems[] = $item['name'];
  }
  $stmt->close();
}

if (!empty($outOfStockItems)) {
  echo "<h2 style='text-align:center;color:red;'>❌ Out of Stock</h2>";
  foreach ($outOfStockItems as $item) {
    echo "<p style='text-align:center;'>$item</p>";
  }
  echo "<p style='text-align:center;'><a href='/php/codeForBothJackets.php'>← Go back</a></p>";
  exit;
}

// ✅ Deduct stock by product ID
foreach ($cartData as $item) {
  $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
  $stmt->bind_param("ii", $item['quantity'], $item['id']);
  $stmt->execute();
  $stmt->close();
}

// ✅ Guest tracking
if (!isset($_SESSION['user_id'])) {
  $session_id = session_id();
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $checkout_time = date('Y-m-d H:i:s');
  $stmt = $conn->prepare("INSERT INTO guest_checkouts (session_id, ip_address, full_name, email, zip_code, checkout_time) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $session_id, $ip_address, $fullname, $email, $zip, $checkout_time);
  $stmt->execute();
  $stmt->close();
}

// ✅ Orders
$user_id = $_SESSION['user_id'] ?? null;
$order_date = date('Y-m-d H:i:s');
$total = 0;
foreach ($cartData as $item) {
  $total += (float)$item['price'] * (int)$item['quantity'];
}

$stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, email, card_last4, total, created_at, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $user_id, $fullname, $address, $email, $cardLast4, $total, $order_date, $order_date);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// ✅ Order Items
foreach ($cartData as $item) {
  $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issid", $order_id, $item['name'], $item['size'], $item['quantity'], $item['price']);
  $stmt->execute();
  $stmt->close();
}

// ✅ Payments
$created_at = date('Y-m-d H:i:s');
$stmt = $conn->prepare("INSERT INTO threadline_payments (fullname, address, email, card_last4, expiry_month, expiry_year, cvv, zip, created_at, order_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssiissi", $fullname, $address, $email, $cardLast4, $expMonth, $expYear, $cvv, $zip, $created_at, $order_id);
$stmt->execute();
$stmt->close();

$conn->close();
?>

<!-- ✅ Confirmation Page -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmed</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div style="max-width:800px;margin:4rem auto;padding:2rem;background:#fff;border-radius:12px;font-family:'Poppins',sans-serif;">
    <h2>✅ Order Confirmed</h2>
    <p>Thank you, <strong><?= $fullname ?></strong>!</p>
    <p>We’ll ship your items to:<br><?= $address ?>, <?= $zip ?></p>
    <p><strong>Order Total:</strong> $<?= number_format($total, 2) ?></p>
    <p><strong>Confirmation sent to:</strong> <?= $email ?></p>
    <a href="/php/codeForBothJackets.php">← Back to Shop</a>
  </div>

  <script>localStorage.removeItem('cart');</script>
</body>
</html>
