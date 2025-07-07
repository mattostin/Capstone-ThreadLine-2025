<?php
require_once "config.php";

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ThreadLine | Products</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom, #a0cfe9, #0766b5);
    }

    header {
      padding: 1.5rem 2rem;
      color: white;
      font-size: 1.8rem;
      font-weight: 700;
    }

    nav {
      position: absolute;
      right: 2rem;
      top: 1.5rem;
    }

    nav a {
      margin-left: 1.5rem;
      color: white;
      font-weight: 600;
      text-decoration: none;
    }

    h2 {
      text-align: center;
      margin: 1rem 0 2rem;
      font-size: 2rem;
      color: #111;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
      padding: 0 2rem 3rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .product-card {
      background-color: #fff;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: contain;
      border-radius: 6px;
      background-color: #f9f9f9;
    }

    .product-card h3 {
      margin: 0.75rem 0 0.25rem;
    }

    .product-card p {
      margin: 0.25rem 0 1rem;
    }

    .product-link button {
      background-color: #065fd4;
      color: white;
      border: none;
      padding: 0.6rem 1rem;
      font-weight: 600;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .product-link button:hover {
      background-color: #054bb5;
    }
  </style>
</head>
<body>
  <header>
    ThreadLine
    <nav>
      <a href="checkout.php">Checkout</a>
      <a href="login.php">Login</a>
      <a href="signup.html">Signup</a>
    </nav>
  </header>

  <h2>Our Products</h2>

  <main>
    <div class="product-grid">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($product = $result->fetch_assoc()): ?>
          <div class="product-card">
            <img src="<?= htmlspecialchars($product['image_front']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
            <h3><?= htmlspecialchars($product['product_name']) ?></h3>
            <p>$<?= number_format($product['price'], 2) ?></p>
            <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
              <button>View Product</button>
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="text-align: center;">No products available.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
