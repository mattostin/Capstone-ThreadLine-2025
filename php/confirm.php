<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php'; // Uses PDO connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST["fullname"] ?? '';
    $address = $_POST["address"] ?? '';
    $email = $_POST["email"] ?? '';
    $card = $_POST["card"] ?? '';
    $expiryMonth = $_POST["expiryMonth"] ?? '';
    $expiryYear = $_POST["expiryYear"] ?? '';
    $cvv = $_POST["cvv"] ?? '';
    $zip = $_POST["zip"] ?? '';
    $cartJson = $_POST["cart"] ?? '[]';

    $cart = json_decode($cartJson, true) ?? [];
    $user_id = $_SESSION["user_id"] ?? 1;

    try {
        // 1. Insert into orders
        $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
        $stmtOrder->execute([$user_id]);
        $order_id = $conn->lastInsertId();

        // 2. Insert into payments
        $stmtPayment = $conn->prepare("INSERT INTO threadline_payments (order_id, fullname, address, email, card, expiryMonth, expiryYear, cvv, zip)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtPayment->execute([$order_id, $fullname, $address, $email, $card, $expiryMonth, $expiryYear, $cvv, $zip]);

        // 3. Insert order items
        if (!empty($cart)) {
            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price)
                                        VALUES (?, ?, ?, ?, ?)");
            foreach ($cart as $item) {
                $stmtItem->execute([
                    $order_id,
                    $item['name'] ?? '',
                    $item['size'] ?? '',
                    $item['quantity'] ?? 1,
                    $item['price'] ?? 0
                ]);
            }
        }

        // ✅ Confirmation message + frontend redirect
        echo "<script>
            alert('✅ Payment successful! Thank you for your order.');
            localStorage.removeItem('cart');
            window.location.href = 'codeForBothJackets.php';
        </script>";
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
