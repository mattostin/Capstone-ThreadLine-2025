<?php
session_start();
require_once 'config.php'; // Make sure this connects to your DB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST["fullname"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $card = $_POST["card"];
    $expiryMonth = $_POST["expiryMonth"];
    $expiryYear = $_POST["expiryYear"];
    $cvv = $_POST["cvv"];
    $zip = $_POST["zip"];
    $cartJson = $_POST["cart"] ?? null;
    $cart = $cartJson ? json_decode($cartJson, true) : [];

    // For now, we'll assume a logged-in user with ID 1 (or get from session)
    $user_id = $_SESSION["user_id"] ?? 1;

    // 1. Insert new order into `orders` table
    $orderStmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
    $orderStmt->bind_param("i", $user_id);
    $orderStmt->execute();
    $order_id = $orderStmt->insert_id;
    $orderStmt->close();

    // 2. Insert payment into `threadline_payments` table
    $paymentStmt = $conn->prepare("INSERT INTO threadline_payments (order_id, fullname, address, email, card, expiryMonth, expiryYear, cvv, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $paymentStmt->bind_param("isssssisi", $order_id, $fullname, $address, $email, $card, $expiryMonth, $expiryYear, $cvv, $zip);
    $paymentStmt->execute();
    $paymentStmt->close();

    // 3. Insert each cart item into `order_items`
    if (!empty($cart)) {
        $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
        foreach ($cart as $item) {
            $itemStmt->bind_param("issid", $order_id, $item['name'], $item['size'], $item['quantity'], $item['price']);
            $itemStmt->execute();
        }
        $itemStmt->close();
    }

    // Clear cart (localStorage will still need to be cleared manually on frontend)
    echo "<script>
        alert('Payment successful! Thank you for your order.');
        localStorage.removeItem('cart');
        window.location.href = 'codeForBothJackets.php';
    </script>";
    exit();
}
?>
