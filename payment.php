<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
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
      gap: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .form-row .form-group {
      flex: 1;
      min-width: 220px;
    }

    label {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"] {
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      width: 100%;
    }

    button {
      padding: 0.75rem 1.5rem;
      background-color: #075eb6;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
      width: fit-content;
      align-self: flex-end;
    }

    button:hover {
      background-color: #054a8e;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </header>

  <main class="payment-container">
    <h2>Payment and Billing</h2>
    <form action="confirm.php" method="post">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" required />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="card">Card Number</label>
          <input type="text" name="card" id="card" maxlength="16" required />
        </div>

        <div class="form-group">
          <label for="exp">Expiration Date (MM/YY)</label>
          <input type="text" name="exp" id="exp" placeholder="MM/YY" maxlength="5" required />
        </div>

        <div class="form-group">
          <label for="cvv">CVV</label>
          <input type="text" name="cvv" id="cvv" maxlength="4" required />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="City">City</label>
          <input type="text" name="City" id="City" required />
        </div>

        <div class="form-group">
          <label for="address">Street</label>
          <input type="text" name="address" id="address" required />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="zip">Zip Code</label>
          <input type="text" name="zip" id="zip" required />
        </div>

        <div class="form-group">
          <label for="state">State</label>
          <input type="text" name="state" id="state" required />
        </div>
      </div>

      <button type="submit">Submit Payment</button>
    </form>
  </main>
</body>
</html>
