<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .payment-container {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      font-family: 'Poppins', sans-serif;
    }

    h2 {
      margin-bottom: 1.5rem;
      font-size: 2rem;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }

    label {
      font-weight: 600;
    }

    input[type="text"],
    input[type="email"],
    select {
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      width: 100%;
      max-width: 500px;
    }

    .inline-group {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .inline-group input,
    .inline-group select {
      flex: 1;
      min-width: 100px;
      max-width: 150px;
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
      max-width: 200px;
    }

    button[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="../html/index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="../php/codeForBothJackets.php">Shop</a></li>
      <li><a href="../php/logout.php">Logout</a></li>
    </ul>
  </header>

  <main class="payment-container">
    <h2>Payment and Billing</h2>
    <form action="../php/confirm.php" method="post">
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" required>

      <label for="address">Address</label>
      <input type="text" id="address" name="address" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="card">Credit Card Number</label>
      <input type="text" id="card" name="card" required>

      <div class="inline-group">
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
</body>
</html>
