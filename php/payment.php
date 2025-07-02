<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment - ThreadLine</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* [your existing styles here] */
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
      <!-- [your form fields remain unchanged] -->
      <button type="submit">Submit Payment</button>
    </form>
  </main>
</body>
</html>
