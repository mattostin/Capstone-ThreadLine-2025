<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Gray Jacket - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .product-detail-container {
      max-width: 1000px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 10px;
      display: flex;
      gap: 40px;
      align-items: center;
      justify-content: space-around;
    }
    .product-detail-container img {
      width: 400px;
      height: auto;
      object-fit: contain;
      border-radius: 10px;
      border: 1px solid #ccc;
    }
    .product-info {
      max-width: 400px;
    }
    .product-info h2 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    .product-info p {
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }
    .size-options {
      display: flex;
      gap: 10px;
      margin-bottom: 1rem;
    }
    .size-options button {
      padding: 0.5rem 1rem;
      border: 1px solid #888;
      background-color: white;
      cursor: pointer;
      border-radius: 6px;
    }
    .size-options button.active {
      background-color: #075eb6;
      color: white;
      font-weight: bold;
    }
    .qty-add {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1.5rem;
    }
    .qty-add input {
      width: 60px;
      padding: 0.5rem;
    }
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
          id: 2,
          name: "Gray Jacket - Size " + selectedSize,
          price: 55,
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
    <img src="gray-front.png" alt="Gray Jacket" />
    <div class="product-info">
      <h2>Men's Softness Sport Jacket - Gray</h2>
      <p>$55</p>
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
