<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_data'])) {
  $user_id = $_SESSION['user_id'];
  $cart = json_decode($_POST['cart_data'], true);
  $errors = [];

  $conn->begin_transaction();

  try {
    // Create order
    $order_sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Prepare once
    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stock_stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

    foreach ($cart as $item) {
      $product_id = $item['id'];
      $quantity = $item['quantity'];
      $price = $item['price'];

      // Update stock
      $stock_stmt->bind_param("iii", $quantity, $product_id, $quantity);
      $stock_stmt->execute();

      if ($stock_stmt->affected_rows === 0) {
        $errors[] = "Not enough stock for product ID $product_id.";
        continue; // skip adding order_item
      }

      // Insert item
      $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
      $item_stmt->execute();
    }

    $item_stmt->close();
    $stock_stmt->close();

    if (!empty($errors)) {
      $conn->rollback();
      echo "<h2>❌ Order failed:</h2><ul>";
      foreach ($errors as $e) echo "<li>$e</li>";
      echo "</ul><a href='checkout.php'>Back to Cart</a>";
    } else {
      $conn->commit();
      echo "<h2>✅ Payment successful! Your order has been placed.</h2>";
      echo "<script>localStorage.removeItem('cart');</script>";
    }
    $conn->close();
    exit;

  } catch (Exception $e) {
    $conn->rollback();
    die("Order failed: " . $e->getMessage());
  }
}
?>
