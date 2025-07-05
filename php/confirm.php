<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname     = $_POST["fullname"];
    $address      = $_POST["address"];
    $email        = $_POST["email"];
    $card_full    = $_POST["card"];
    $card_last4   = substr(preg_replace('/\D/', '', $card_full), -4); // Only digits, last 4
    $expiryMonth  = $_POST["expiryMonth"];
    $expiryYear   = $_POST["expiryYear"];
    $cvv          = $_POST["cvv"];
    $zip          = $_POST["zip"];
    $cartJson     = $_POST["cart"] ?? null;
    $cart         = $cartJson ? json_decode($cartJson, true) : [];

    $user_id = $_SESSION["user_id"] ?? 1;

    // 1. Insert into `orders`
    $orderStmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
    $orderStmt->bind_param("i", $user_id);
    $orderStmt->execute();
    $order_id = $orderStmt->insert_id;
    $orderStmt->close();

    // 2. Insert into `threadline_payments`
    $paymentStmt = $conn->prepare("INSERT INTO threadline_payments (order_id, fullname, address, email, card_last4, expiry_month, expiry_year, cvv, zip, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $paymentStmt->bind_param("isssssisi", $order_id, $fullname, $address, $email, $card_last4, $expiryMonth, $expiryYear, $cvv, $zip);
    $paymentStmt->execute();
    $paymentStmt->close();

    // 3. Insert cart items into `order_items`
    if (!empty($cart)) {
        $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
        foreach ($cart as $item) {
            $itemStmt->bind_param("issid", $order_id, $item['name'], $item['size'], $item['quantity'], $item['price']);
            $itemStmt->execute();
        }
        $itemStmt->close();
    }

    echo "<script>
        alert('✅ Payment successful! Thank you for your order.');
        localStorage.removeItem('cart');
        window.location.href = 'codeForBothJackets.php';
    </script>";
    exit();
}
?>
