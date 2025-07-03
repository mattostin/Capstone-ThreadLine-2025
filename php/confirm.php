<?php
session_start();

// Connect to your DB
$host = "localhost"; // or use your actual host
$dbname = "thredqwx_threadline";
$username = "root"; // or your MySQL user
$password = "";     // or your MySQL password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Collect POST data safely
$fullname = $_POST['fullname'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$card = $_POST['card'] ?? '';
$card_last4 = substr(preg_replace("/\D/", "", $card), -4); // store last 4 only
$expiryMonth = $_POST['expiryMonth'] ?? '';
$expiryYear = $_POST['expiryYear'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$zip = $_POST['zip'] ?? '';

// Insert into DB
$stmt = $pdo->prepare("INSERT INTO threadline_payments 
  (fullname, address, email, card_last4, expiry_month, expiry_year, cvv, zip) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$fullname, $address, $email, $card_last4, $expiryMonth, $expiryYear, $cvv, $zip]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .confirmation {
      max-width: 800px;
      margin: 4rem auto;
      background: #ffffffdd;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      font-family: 'Poppins', sans-serif;
      text-align: center;
    }
    .confirmation h2 {
      margin-bottom: 1rem;
    }
    .confirmation p {
      margin: 0.5rem 0;
    }
    .confirmation .back-link {
      display: inline-block;
      margin-top: 2rem;
      text-decoration: none;
      color: #007bff;
      font-weight: 600;
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

  <main class="confirmation">
    <h2>Payment Successful</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($fullname) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
    <p><strong>Card Ending In:</strong> **** <?= htmlspecialchars($card_last4) ?></p>
    <p><strong>Expiration:</strong> <?= htmlspecialchars($expiryMonth) ?>/<?= htmlspecialchars($expiryYear) ?></p>
    <p><strong>ZIP Code:</strong> <?= htmlspecialchars($zip) ?></p>
    <a class="back-link" href="codeForBothJackets.php">← Continue Shopping</a>
  </main>
</body>
</html>
