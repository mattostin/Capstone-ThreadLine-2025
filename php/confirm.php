<?php
session_start();

// Sanitize and retrieve POST values
$fullname = htmlspecialchars($_POST['fullname'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$cardNumber = preg_replace('/\D/', '', $_POST['card'] ?? '');
$cardLast4 = substr($cardNumber, -4);
$expiryMonth = htmlspecialchars($_POST['expiryMonth'] ?? 'MM');
$expiryYear = htmlspecialchars($_POST['expiryYear'] ?? 'YYYY');
$zip = htmlspecialchars($_POST['zip'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .confirmation-container {
      max-width: 700px;
      margin: 4rem auto;
      padding: 2rem 2.5rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif;
    }

    .confirmation-container h2 {
      font-size: 2.3rem;
      color: #075eb6;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .confirmation-container p {
      font-size: 1.1rem;
      margin: 0.75rem 0;
      color: #333;
    }

    .highlight {
      font-weight: 600;
    }

    .btn-home {
      display: block;
      margin: 2rem auto 0;
      padding: 0.75rem 1.5rem;
      background-color: #075eb6;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 600;
      text-align: center;
      width: fit-content;
    }

    .navbar {
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="../html/index.html" class="logo">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="codeForBothJackets.php">Shop</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </header>

  <div class="confirmation-container">
    <h2>Thank You for Your Purchase!</h2>
    <p>Your order has been received and is being processed. Below are the details:</p>

    <p><span class="highlight">Name:</span> <?= $fullname ?></p>
    <p><span class="highlight">Email:</span> <?= $email ?></p>
    <p><span class="highlight">Shipping Address:</span> <?= $address ?></p>
    <p><span class="highlight">ZIP Code:</span> <?= $zip ?></p>
    <p><span class="highlight">Card Used:</span> **** **** **** <?= $cardLast4 ?> (Exp: <?= $expiryMonth ?>/<?= $expiryYear ?>)</p>

    <p>You will receive a confirmation email shortly with your order summary and tracking details.</p>

    <a class="btn-home" href="../html/index.html">Back to Homepage</a>
  </div>
</body>
</html>
