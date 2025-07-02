<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>White Jacket - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .product-detail-container {
      display: flex;
      justify-content: center;
      padding: 4rem 2rem;
    }

    .product-detail-box {
      background-color: #ffffffcc;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
      padding: 2rem;
      max-width: 700px;
      width: 100%;
      text-align: center;
    }

    .product-detail-images {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 1.5rem;
    }

    .product-detail-images img {
      width: 200px;
      height: auto;
      border-radius: 6px;
      border: 1px solid #ccc;
      object-fit: contain;
    }

    .product-detail-box p {
      font-size: 1.2rem;
      font-weight: 500;
      margin-top: 0.5rem;
    }

    .product-detail-box strong {
      font-size: 1.3rem;
      display: block;
      margin-top: 0.25rem;
    }

    form label {
      display: block;
      margin-top: 1rem;
      font-weight: bold;
    }

    select, input[type="number"] {
      padding: 0.6rem;
      margin-top: 0.5rem;
      width: 80%;
      font-size: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .add-to-cart-btn {
      margin-top: 1.5rem;
      width: 85%;
      padding: 0.8rem;
      font-size: 1rem;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('addToCartForm');
      form.addEventListener('submit', e => {
        e.preventDefault();
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const size = document.getElementById('size').value;
        const quantity = parseInt(document.getElementById('quantity').value);
        const existingIndex = cart.findIndex(
          item => item.id === 1 && item.size === size
        );

        if (existingIndex > -1) {
          cart[existingIndex].quantity += quantity;
        } else {
          cart.push({
            id: 1,
            name: "White Jacket",
            price: 55,
            quantity: quantity,
            size: size
          });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        alert("White Jacket added to cart!");
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main class="product-detail-container">
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="white-frontt.png" alt="White Jacket Front">
        <img src="white-back.png" alt="White Jacket Back">
      </div>
      <p>Men's Softness Sport Jacket - White</p>
      <strong>$55</strong>

      <form id="addToCartForm">
        <label for="size">Size:</label>
        <select id="size" name="size" required>
          <option value="">Select</option>
          <option value="S">Small</option>
          <option value="M">Medium</option>
          <option value="L">Large</option>
          <option value="XL">X-Large</option>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required />

        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
