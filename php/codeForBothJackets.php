<?php
// Secure session setup
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Database connection
date_default_timezone_set('America/Los_Angeles');
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ✅ Update last_activity if logged in
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $now = date('Y-m-d H:i:s');
  $updateSql = "UPDATE users SET last_activity = ? WHERE id = ?";
  $stmt = $conn->prepare($updateSql);
  $stmt->bind_param("si", $now, $user_id);
  $stmt->execute();
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
      padding: 0 2rem;
    }

    .product-box {
      background-color: #fff;
      padding: 1rem;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      font-family: 'Poppins', sans-serif;
    }

    .product-box img {
      width: 100%;
      max-width: 200px;
      height: auto;
      object-fit: contain;
    }

    .product-box h3 {
      margin: 0.5rem 0;
    }

    .product-box button {
      background-color: #075eb6;
      color: white;
      border: none;
      padding: 0.6rem 1rem;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <a class="logo" href="<?= isset($_SESSION['username']) ? '/php/home.php' : '/html/index.html' ?>">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
          <li><a href="admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <main style="padding: 2rem;">
    <h1 style="text-align:center;">Our Products</h1>
    <div class="product-grid">
      <?php
      $sql = "SELECT * FROM products";
      $result = $conn->query($sql);

      if ($result->num_rows > 0):
        while ($product = $result->fetch_assoc()):
      ?>
        <div class="product-box">
          <a href="product.php?id=<?= $product['id'] ?>">
            <img src="<?= $product['image_front'] ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
          </a>
          <h3><?= htmlspecialchars($product['product_name']) ?></h3>
          <p>$<?= number_format($product['price'], 2) ?></p>
          <a href="product.php?id=<?= $product['id'] ?>">
            <button>View Product</button>
          </a>
        </div>
      <?php
        endwhile;
      else:
        echo "<p>No products available.</p>";
      endif;
      ?>
    </div>
  </main>
</body>
</html>
