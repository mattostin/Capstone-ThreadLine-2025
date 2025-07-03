<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>White Shorts - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .product-detail-box {
      max-width: 800px;
      margin: 5rem auto;
      padding: 2rem;
      background-color: #f4f9ff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
    }
    .product-detail-images {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-bottom: 1rem;
    }
    .product-detail-images img {
      max-width: 250px;
      height: auto;
      object-fit: contain;
    }
    .size-selector {
      margin: 0.5rem 0 1rem;
    }
    .size-btn {
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border: 1px solid #333;
      background-color: white;
      cursor: pointer;
    }
    .size-btn.selected {
      background-color: #007bff;
      color: white;
    }
    #addToCartForm input[type="number"] {
      width: 60px;
      padding: 0.3rem;
      margin-right: 1rem;
    }
    #addToCartForm button {
      background-color: #005bbb;
      color: white;
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
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
        const existingIndex = cart.findIndex(item => item.id === 3 && item.size === selectedSize);

        if (existingIndex > -1) {
          cart[existingIndex].quantity += quantity;
        } else {
          cart.push({
            id: 3,
            name: "White Shorts",
            price: 35,
            quantity: quantity,
            size: selectedSize
          });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        alert("White Shorts added to cart!");
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="../html/index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="../php/codeForBothJackets.php">Shop</a></li>
      <li><a href="../php/checkout.php">Checkout</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
        <li><a href="../php/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="../php/login.php">Login</a></li>
        <li><a href="../php/signup.php">Signup</a></li>
      <?php endif; ?>
    </ul>
  </header>

  <main>
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="../images/whiteShortFront.png" alt="White Shorts Front">
        <img src="../images/whiteShortBack.png" alt="White Shorts Back">
      </div>
      <p>Men's Soft Cotton Shorts - White</p>
      <strong>$35</strong>

      <form id="addToCartForm">
        <label style="font-size: 1.4rem;">Size:</label>
        <div class="size-selector">
          <button type="button" class="size-btn" data-size="S">S</button>
          <button type="button" class="size-btn" data-size="M">M</button>
          <button type="button" class="size-btn" data-size="L">L</button>
          <button type="button" class="size-btn" data-size="XL">XL</button>
        </div>

        <label for="quantity" style="font-size: 1.4rem;">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" />
        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
