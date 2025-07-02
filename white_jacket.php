<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>White Jacket - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .product-fullscreen {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      min-height: 100vh;
      padding: 3rem 2rem;
    }

    .product-detail-box {
      width: 100%;
      max-width: 1100px;
      background-color: #ffffffcc;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
      padding: 2.5rem;
      text-align: center;
    }

    .product-detail-images {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      margin-bottom: 2rem;
    }

    .product-detail-images img {
      width: 300px;
      max-width: 100%;
      height: auto;
      border-radius: 6px;
      border: 1px solid #ccc;
      object-fit: contain;
    }

    .product-detail-box p {
      font-size: 1.4rem;
      font-weight: 500;
      margin-top: 0.5rem;
    }

    .product-detail-box strong {
      font-size: 1.5rem;
      display: block;
      margin: 0.25rem 0 1rem;
    }

    .size-selector {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
      flex-wrap: wrap;
    }

    .size-btn {
      padding: 0.75rem 1.5rem;
      border: 2px solid #075eb6;
      background-color: white;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
      font-size: 1rem;
    }

    .size-btn.selected,
    .size-btn:hover {
      background-color: #075eb6;
      color: white;
    }

    input[type="number"] {
      padding: 0.75rem;
      margin-top: 1rem;
      width: 80px;
      font-size: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      text-align: center;
    }

    .add-to-cart-btn {
      margin-top: 1.5rem;
      width: 85%;
      max-width: 300px;
      padding: 0.9rem;
      font-size: 1.1rem;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
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
        const existingIndex = cart.findIndex(
          item => item.id === 1 && item.size === selectedSize
        );

        if (existingIndex > -1) {
          cart[existingIndex].quantity += quantity;
        } else {
          cart.push({
            id: 1,
            name: "White Jacket",
            price: 55,
            quantity: quantity,
            size: selectedSize
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

  <main class="product-fullscreen">
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="white-frontt.png" alt="White Jacket Front">
        <img src="white-back.png" alt="White Jacket Back">
      </div>
      <p>Men's Softness Sport Jacket - White</p>
      <strong>$55</strong>

      <form id="addToCartForm">
        <label style="margin-top: 1rem;">Size:</label>
        <div class="size-selector">
          <button type="button" class="size-btn" data-size="S">S</button>
          <button type="button" class="size-btn" data-size="M">M</button>
          <button type="button" class="size-btn" data-size="L">L</button>
          <button type="button" class="size-btn" data-size="XL">XL</button>
        </div>

        <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required />

        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
