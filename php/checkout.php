<?php
if (session_status() === PHP_SESSION_NONE) {
  session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
  ]);
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - ThreadLine</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/css/style.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f2f2f2;
    }

    .checkout-container {
      max-width: 900px;
      margin: 3rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    h2 {
      margin-bottom: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2rem;
    }

    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .total {
      text-align: right;
      font-size: 1.2rem;
      font-weight: bold;
    }

    .empty-cart {
      text-align: center;
      font-size: 1.1rem;
      color: #666;
      padding: 2rem 0;
    }

    .btn-checkout {
      background: #005bbb;
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    .btn-clear {
      background: #666;
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      margin-right: 1rem;
    }
  </style>
</head>
<body>

  <?php include 'navbar.php'; ?>

  <script>
    if (sessionStorage.getItem("clearCartOnLogin") === "true") {
      localStorage.removeItem("cart");
      sessionStorage.removeItem("clearCartOnLogin");
    }
  </script>

  <div class="checkout-container">
    <h2>Shopping Cart</h2>
    <div id="cartContainer"></div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <button class="btn-clear" onclick="clearCart()">Clear Cart</button>

      <div>
        <?php
        if (isset($_SESSION['username'])) {
          echo '<a href="/php/payment.php"><button class="btn-checkout">Proceed to Payment</button></a>';
        } elseif (isset($_SESSION['guest']) && $_SESSION['guest'] === true) {
          echo '<a href="/php/payment.php"><button class="btn-checkout">Proceed as Guest</button></a>';
        } else {
          echo '<a href="/php/guest_checkout.php"><button class="btn-checkout">Continue as Guest</button></a>';
        }
        ?>
      </div>
    </div>
  </div>

  <script>
    function renderCart() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const container = document.getElementById('cartContainer');

      if (cart.length === 0) {
        container.innerHTML = '<p class="empty-cart">Your cart is empty.</p>';
        return;
      }

      let html = '<table><thead><tr><th>Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>';
      let total = 0;

      cart.forEach(item => {
        const subtotal = item.quantity * item.price;
        total += subtotal;
        html += `<tr>
          <td>${item.name}</td>
          <td>${item.size}</td>
          <td>${item.quantity}</td>
          <td>$${item.price.toFixed(2)}</td>
          <td>$${subtotal.toFixed(2)}</td>
        </tr>`;
      });

      html += `</tbody></table><p class="total">Total: $${total.toFixed(2)}</p>`;
      container.innerHTML = html;
    }

    function clearCart() {
      localStorage.removeItem('cart');
      renderCart();
    }

    renderCart();
  </script>
<script>
  window.loggedInUser = <?= isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'null' ?>;
  window.productId = null;
</script>
<script src="/javascript/tracker.js"></script>

</body>
</html>
