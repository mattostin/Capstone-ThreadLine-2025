<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Database connection
$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_data'])) {
  $user_id = $_SESSION['user_id'];
  $cart = json_decode($_POST['cart_data'], true);

  // Create order
  $order_sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
  $stmt = $conn->prepare($order_sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $order_id = $stmt->insert_id;
  $stmt->close();

  // Insert order items and update stock
  foreach ($cart as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    // Insert into order_items
    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_sql);
    $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $item_stmt->execute();
    $item_stmt->close();

    // Update stock
    $stock_sql = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
    $stock_stmt = $conn->prepare($stock_sql);
    $stock_stmt->bind_param("iii", $quantity, $product_id, $quantity);
    $stock_stmt->execute();
    $stock_stmt->close();
  }

  $conn->close();
  echo "<h2>✅ Payment successful! Your order has been placed.</h2>";
  echo "<script>localStorage.removeItem('cart');</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="payment-container">
    <h2>Enter Payment Details</h2>
    <form method="POST" id="payment-form">
      <input type="text" name="cardholder" placeholder="Cardholder Name" required><br>
      <input type="text" name="cardnumber" placeholder="Card Number" required><br>
      <input type="text" name="expiry" placeholder="MM/YY" required><br>
      <input type="text" name="cvv" placeholder="CVV" required><br>
      <input type="hidden" name="cart_data" id="cart_data_field">
      <button type="submit">Pay Now</button>
    </form>
  </div>

  <script>
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    document.getElementById('cart_data_field').value = JSON.stringify(cart);
  </script>
</body>
</html>
