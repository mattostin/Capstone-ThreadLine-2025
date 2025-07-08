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
    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 2.5rem;
      justify-items: center;
    }

    .product-link {
      text-decoration: none;
      color: inherit;
    }

    .product-box {
      background-color: #fff;
      padding: 1rem;
      border-radius: 10px;
      text-align: center;
      width: 100%;
      max-width: 250px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      font-family: 'Poppins', sans-serif;
      transition: transform 0.2s ease;
    }

    .product-box:hover {
      transform: scale(1.02);
    }

    .product-box img {
      width: 100%;
      height: 200px;
      object-fit: contain;
      margin-bottom: 1rem;
    }

    .product-box button {
      background-color: #075eb6;
      color: white;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 0.5rem;
    }

    .product-box h3 {
      margin: 0.5rem 0;
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

  <main>
    <h1 style="text-align:center;">Our Products</h1>
    <div class="container">
      <div class="product-grid">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0):
          while ($product = $result->fetch_assoc()):
        ?>
          <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
            <div class="product-box">
              <img src="/<?= htmlspecialchars($product['image_front']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
              <h3><?= htmlspecialchars($product['product_name']) ?></h3>
              <p>$<?= number_format($product['price'], 2) ?></p>
              <button>View Product</button>
            </div>
          </a>
        <?php
          endwhile;
        else:
          echo "<p>No products available.</p>";
        endif;
        ?>
      </div>
    </div>
  </main>

  <!-- TRACKING SCRIPT -->
  <script>
    let startTime = Date.now();
    window.addEventListener("beforeunload", function () {
      const duration = Math.round((Date.now() - startTime) / 1000);
      navigator.sendBeacon("/track_view.php", JSON.stringify({
        user_id: <?= isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'null' ?>,
        page_visited: window.location.pathname,
        product_id: null,
        duration_seconds: duration
      }));
    });
  </script>
</body>
</html>
