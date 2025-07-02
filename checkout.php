<?php
session_start();
date_default_timezone_set('America/Los_Angeles');

// Simulate some products (replace with real cart logic)
$products = [
  ['name' => 'White Jacket', 'price' => 55, 'id' => 1],
  ['name' => 'Gray Jacket', 'price' => 55, 'id' => 2],
  ['name' => 'Green Shorts', 'price' => 35, 'id' => 3]
];

// Simulate cart (in a real site, use $_SESSION or database)
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST['quantity'] as $id => $qty) {
        $cart[$id] = max(0, (int)$qty);
    }
    $_SESSION['cart'] = $cart;
}

function getProduct($id, $products) {
    foreach ($products as $product) {
        if ($product['id'] == $id) return $product;
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <nav class="navbar">
    <a href="index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="checkout-container">
    <h2>Your Cart</h2>
    <form method="POST">
      <table border="1" cellpadding="10">
        <tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
        <?php
        $total = 0;
        foreach ($cart as $id => $qty):
            $product = getProduct($id, $products);
            if (!$product) continue;
            $subtotal = $product['price'] * $qty;
            $total += $subtotal;
        ?>
        <tr>
          <td><?= htmlspecialchars($product['name']) ?></td>
          <td>$<?= $product['price'] ?></td>
          <td>
            <input type="number" name="quantity[<?= $id ?>]" value="<?= $qty ?>" min="0" />
          </td>
          <td>$<?= $subtotal ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3"><strong>Total:</strong></td>
          <td><strong>$<?= $total ?></strong></td>
        </tr>
      </table>
      <br>
      <button type="submit">Update Cart</button>
      <button onclick="alert('Order placed!'); return false;">Place Order</button>
    </form>
  </div>
</body>
</html>
