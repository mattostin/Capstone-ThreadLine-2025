<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Green Shorts - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Same styling as above (you can reuse it in a common CSS block if you prefer) */
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const sizeButtons = document.querySelectorAll('.size-btn');
      let selectedSize = null;

      sizeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          sizeButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          selectedSize = btn.dataset.size;
        });
      });

      document.getElementById('add-to-cart-btn').addEventListener('click', () => {
        const qty = parseInt(document.getElementById('qty').value);
        if (!selectedSize) {
          alert("Please select a size.");
          return;
        }

        const item = {
          id: 3,
          name: "Green Shorts - Size " + selectedSize,
          price: 35,
          quantity: qty
        };

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push(item);
        localStorage.setItem('cart', JSON.stringify(cart));
        alert("Added to cart!");
      });
    });
  </script>
</head>
<body>
  <header class="navbar">
    <a href="index.html" class="logo" style="font-weight: 600;">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="checkout.php">Checkout</a></li>
    </ul>
  </header>

  <main class="product-detail-container">
    <img src="greenShortFront.png" alt="Green Shorts" />
    <div class="product-info">
      <h2>Men's Everyday Shorts - Green</h2>
      <p>$35</p>
      <div class="size-options">
        <button class="size-btn" data-size="S">S</button>
        <button class="size-btn" data-size="M">M</button>
        <button class="size-btn" data-size="L">L</button>
        <button class="size-btn" data-size="XL">XL</button>
      </div>
      <div class="qty-add">
        <label for="qty">Quantity:</label>
        <input type="number" id="qty" value="1" min="1" />
      </div>
      <button class="add-to-cart-btn" id="add-to-cart-btn">Add to Cart</button>
    </div>
  </main>
</body>
</html>