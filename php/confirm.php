<?php
session_start();

// STEP 1: Database connection
$host = 'localhost';
$dbname = 'thredqwx_threadline';
$username = 'thredqwx_cpanel-key'; // ✅ use your actual DB username
$password = 'Mostin2003$$';   // ✅ replace with your actual password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// STEP 2: Sanitize & collect form data
$fullname     = $_POST['fullname'] ?? '';
$address      = $_POST['address'] ?? '';
$email        = $_POST['email'] ?? '';
$card         = $_POST['card'] ?? '';
$expiryMonth  = $_POST['expiryMonth'] ?? '';
$expiryYear   = $_POST['expiryYear'] ?? '';
$cvv          = $_POST['cvv'] ?? '';
$zip          = $_POST['zip'] ?? '';

// Only store last 4 digits of card for privacy
$card_last4 = substr(preg_replace('/\D/', '', $card), -4);

// STEP 3: Insert into DB
$sql = "INSERT INTO threadline_payments 
        (fullname, address, email, card_last4, expiry_month, expiry_year, cvv, zip, created_at) 
        VALUES 
        (:fullname, :address, :email, :card_last4, :expiry_month, :expiry_year, :cvv, :zip, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':fullname'     => $fullname,
    ':address'      => $address,
    ':email'        => $email,
    ':card_last4'   => $card_last4,
    ':expiry_month' => $expiryMonth,
    ':expiry_year'  => $expiryYear,
    ':cvv'          => $cvv,
    ':zip'          => $zip
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    .confirmation-container {
      max-width: 700px;
      margin: 5rem auto;
      background-color: #fff;
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h2 {
      color: #28a745;
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    p {
      font-size: 1.1rem;
      color: #333;
      margin-bottom: 0.75rem;
    }

    .btn-home {
      display: inline-block;
      margin-top: 1.5rem;
      padding: 0.75rem 1.5rem;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .btn-home:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="confirmation-container">
    <h2>✅ Payment Successful!</h2>
    <p>Thank you, <strong><?= htmlspecialchars($fullname) ?></strong>!</p>
    <p>Your order has been received and is being processed.</p>
    <p>A receipt has been sent to <strong><?= htmlspecialchars($email) ?></strong>.</p>
    <p>Last 4 digits of card: <strong>**** <?= htmlspecialchars($card_last4) ?></strong></p>

    <a href="../php/codeForBothJackets.php" class="btn-home">Back to Shop</a>
  </div>
</body>
</html>
