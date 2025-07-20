<?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

<?php
    
// Force HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

// Secure session settings
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['guest'])) {
  header("Location: ../php/login.php?redirect=payment.php");
  exit();
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    
  <style>
    .payment-container {
      max-width: 700px;
      margin: 4rem auto;
      padding: 2rem 2.5rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
    }

    h2 {
      margin-bottom: 2rem;
      font-size: 2rem;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    label {
      font-weight: 600;
      margin-bottom: 0.25rem;
      display: block;
    }

    input[type="text"],
    input[type="email"],
    select {
      padding: 0.65rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      width: 100%;
    }

    .flex-row {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .flex-row > div {
      flex: 1;
      min-width: 120px;
    }

    button[type="submit"] {
      background-color: #007bff;
      color: white;
      padding: 0.75rem;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 1rem;
    }

    button[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <main class="payment-container">
    <h2>Payment and Billing</h2>
    <form action="../php/confirm.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

      <div>
        <label for="fullname">Full Name</label>
        <input type="text" id="fullname" name="fullname" required>
      </div>

      <div>
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>
      </div>

      <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div>
        <label for="card">Credit Card Number</label>
        <input type="text" id="card" name="card" required>
      </div>

      <div class="flex-row">
        <div>
          <label for="expiryMonth">Exp. Month</label>
          <select id="expiryMonth" name="expiryMonth" required>
            <option value="" disabled selected>MM</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= sprintf('%02d', $i) ?>"><?= sprintf('%02d', $i) ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div>
          <label for="expiryYear">Exp. Year</label>
          <select id="expiryYear" name="expiryYear" required>
            <option value="" disabled selected>YYYY</option>
            <?php
              $year = date('Y');
              for ($i = 0; $i < 10; $i++) {
                echo "<option value='".($year + $i)."'>".($year + $i)."</option>";
              }
            ?>
          </select>
        </div>

        <div>
          <label for="cvv">CVV</label>
          <input type="text" id="cvv" name="cvv" maxlength="4" required>
        </div>

        <div>
          <label for="zip">ZIP Code</label>
          <input type="text" id="zip" name="zip" maxlength="10" required>
        </div>
      </div>

      <button type="submit">Submit Payment</button>
    </form>
  </main>

  <script>
    document.querySelector("form").addEventListener("submit", function () {
      const cart = localStorage.getItem("cart");
      if (cart) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "cart";
        input.value = cart;
        this.appendChild(input);
      }
    });
  </script>

  <script>
  window.loggedInUser = <?= isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'null' ?>;
  window.productId = null;
</script>
<script src="/javascript/tracker.js"></script>
</body>
</html>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

