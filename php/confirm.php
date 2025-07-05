<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Get form values
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$email = $_POST['email'];
$card = $_POST['card'];
$last4 = substr($card, -4);
$expiryMonth = $_POST['expiryMonth'];
$expiryYear = $_POST['expiryYear'];
$cvv = $_POST['cvv'];
$zip = $_POST['zip'];
$cart = json_decode($_POST['cart'], true);

// 1. Get most recent order for this user
$orderStmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$orderStmt->bind_param("i", $user_id);
$orderStmt->execute();
$orderStmt->bind_result($order_id);
$orderStmt->fetch();
$orderStmt->close();

if (!$order_id) {
  die("No order found for this user.");
}

// 2. Update order with billing info
$update = $conn->prepare("UPDATE orders SET fullname = ?, address = ?, email = ?, card_last4 = ? WHERE id = ?");
$update->bind_param("ssssi", $fullname, $address, $email, $last4, $order_id);
$update->execute();

// 3. Save payment
$payment = $conn->prepare("INSERT INTO threadline_payments (order_id) VALUES (?)");
$payment->bind_param("i", $order_id);
$payment->execute();

// 4. Confirm to user
echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Payment Confirmation</title>
<link rel='stylesheet' href='../css/style.css'>
<style>
  body { font-family: 'Poppins', sans-serif; text-align: center; padding: 50px; background: #f4f4f4; }
  .confirmation-box { background: white; padding: 2rem; border-radius: 8px; display: inline-block; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
  .confirmation-box h2 { color: green; }
</style>
</head><body><div class='confirmation-box'>
<h2>Payment Successful!</h2>
<p>Thank you for your purchase, <strong>$fullname</strong>.</p>
<p>Order ID: <strong>$order_id</strong></p>
<p>Card ending in ****$last4</p>
<a href='../html/index.html'>Return Home</a>
</div></body></html>";
?>
