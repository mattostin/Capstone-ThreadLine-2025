<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .payment-container {
      max-width: 600px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .payment-container h2 {
      margin-bottom: 1.5rem;
    }

    .payment-container input {
      width: 100%;
      margin-bottom: 1rem;
      padding: 0.75rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .payment-container button {
      background-color: #075eb6;
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
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

  <div class="payment-container">
    <h2>Billing Information</h2>
    <form>
      <input type="text" placeholder="Full Name" required>
      <input type="text" placeholder="Address" required>
      <input type="text" placeholder="Card Number" required>
      <input type="text" placeholder="Expiration Date" required>
      <input type="text" placeholder="CVV" required>
      <button type="submit">Submit Payment</button>
    </form>
  </div>
</body>
</html>
