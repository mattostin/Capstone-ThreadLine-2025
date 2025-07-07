<?php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "config.php"; // uses the $conn from config.php

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
  echo "Product not found.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['product_name']) ?> - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    .product-detail-box { max-width: 800px; margin: 5rem auto; padding: 2rem; background: #f4f9ff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Poppins', sans-serif; }
    .product-detail-images { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1rem; }
    .product-detail-images img { max-width: 250px; object-fit: contain; }
    .size-selector { margin: 0.5rem 0 1rem; }
    .size-btn { padding: 0.5rem 1rem; margin-right: 0.5rem; border: 1px solid #333; background: white; cursor: pointer; }
    .size-btn.selected { background: #007bff; color: white; }
    #addToCartForm input[type="number"] { width: 60px; padding: 0.3rem; margin-right: 1rem; }
    #addToCartForm button { background: #005bbb; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="<?= isset($_SESSION['username']) ? '/php/home.php' : '/html/index.html' ?>" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li><a href="/php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if ($_SESSION['email'] === 'admin@threadline.com'): ?>
          <li><a href="/php/admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li style="color: white;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="/php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/php/login.php">Login</a></li>
        <li><a href="/php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main>
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="<?= htmlspecialchars($product['image_front']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?> Front">
        <img src="<?= htmlspecialchars($product['image_back']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?> Back">
      </div>
      <p><?= htmlspecialchars($product['product_name']) ?></p>
      <strong>$<?= number_format($product['price'], 2) ?></strong>

      <form id="addToCartForm">
        <label style="font-size: 1.2rem;">Size:</label>
        <div class="size-selector">
          <?php foreach (explode(',', $product['available_sizes']) as $size): ?>
            <button type="button" class="size-btn" data-size="<?= $size ?>"><?= strtoupper($size) ?></button>
          <?php endforeach; ?>
        </div>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" value="1" min="1" />
        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </main>

  <script>
    let selectedSize = "";
    document.querySelectorAll('.size-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedSize = btn.dataset.size;
      });
    });

    document.getElementById('addToCartForm').addEventListener('submit', e => {
      e.preventDefault();
      const quantity = parseInt(document.getElementById('quantity').value);
      if (!selectedSize) {
        alert("Please select a size.");
        return;
      }

      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const existingIndex = cart.findIndex(item => item.id === <?= $product['id'] ?> && item.size === selectedSize);

      if (existingIndex > -1) {
        cart[existingIndex].quantity += quantity;
      } else {
        cart.push({
          id: <?= $product['id'] ?>,
          name: <?= json_encode($product['product_name']) ?>,
          price: <?= $product['price'] ?>,
          quantity,
          size: selectedSize
        });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      alert("<?= $product['product_name'] ?> added to cart!");
    });
  </script>
</body>
</html>
