<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_data'])) {
  $user_id = $_SESSION['user_id'];
  $cart = json_decode($_POST['cart_data'], true);

  if (empty($cart)) {
    echo "<h2>Your cart is empty.</h2>";
    exit;
  }

  // Start transaction
  $conn->begin_transaction();

  try {
    // Insert order
    $order_sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    foreach ($cart as $item) {
      $product_id = $item['id'];
      $quantity = $item['quantity'];
      $price = $item['price'];

      // Check stock
      $stock_check = $conn->prepare("SELECT stock FROM products WHERE id = ?");
      $stock_check->bind_param("i", $product_id);
      $stock_check->execute();
      $stock_check->bind_result($stock);
      $stock_check->fetch();
      $stock_check->close();

      if ($stock < $quantity) {
        throw new Exception("Not enough stock for product ID $product_id.");
      }

      // Insert into order_items
      $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
      $insert_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
      $insert_item->execute();
      $insert_item->close();

      // Update stock
      $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
      $update_stock->bind_param("ii", $quantity, $product_id);
      $update_stock->execute();
      $update_stock->close();
    }

    $conn->commit();
    echo "<h2>✅ Payment successful! Your order has been placed.</h2>";
    echo "<script>localStorage.removeItem('cart');</script>";
  } catch (Exception $e) {
    $conn->rollback();
    echo "<h2>❌ Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
  }

  $conn->close();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .payment-container {
      max-width: 500px;
      margin: 4rem auto;
      padding: 2rem;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      font-family: 'Poppins', sans-serif;
    }

    input, button {
      width: 100%;
      padding: 1rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    button {
      background-color: #075eb6;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #054a8e;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <h2>Enter Payment Info</h2>
    <form method="POST">
      <input type="text" name="cardholder" placeholder="Cardholder Name" required>
      <input type="text" name="cardnumber" placeholder="Card Number" required>
      <input type="text" name="expiry" placeholder="MM/YY" required>
      <input type="text" name="cvv" placeholder="CVV" required>
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
