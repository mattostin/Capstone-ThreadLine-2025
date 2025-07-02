<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>White Jacket - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
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

  <main style="display: flex; justify-content: center; padding: 2rem;">
    <div class="product-box">
      <div class="product-images bg-white">
        <img src="white-frontt.png" alt="White Jacket Front" />
        <img src="white-back.png" alt="White Jacket Back" />
      </div>
      <p>Men's Softness Sport Jacket - White<br><strong>$55</strong></p>

      <form id="addToCartForm" class="signup-form" style="margin-top: 1rem;">
        <label for="size">Size:</label>
        <select id="size" name="size" required>
          <option value="">Select</option>
          <option value="S">Small</option>
          <option value="M">Medium</option>
          <option value="L">Large</option>
          <option value="XL">X-Large</option>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" class="qty-input" min="1" value="1" required />

        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
      </form>
    </div>
  </main>
</body>
</html>
