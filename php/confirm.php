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
    $card_last4   = substr(preg_replace('/\D/', '', $card_full), -4); // Keep last 4 digits only
    $expiryMonth  = $_POST["expiryMonth"];
    $expiryYear   = $_POST["expiryYear"];
    $cvv          = $_POST["cvv"];
    $zip          = $_POST["zip"];
    $cartJson     = $_POST["cart"] ?? null;
    $cart         = $cartJson ? json_decode($cartJson, true) : [];

    $user_id = $_SESSION["user_id"] ?? 1;

    try {
        // 1. Insert into `orders`
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
        $stmt->execute([$user_id]);
        $order_id = $conn->lastInsertId();

        // 2. Insert into `threadline_payments`
        $stmt = $conn->prepare("INSERT INTO threadline_payments (
            order_id, fullname, address, email, card_last4, expiry_month, expiry_year, cvv, zip, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $order_id, $fullname, $address, $email, $card_last4,
            $expiryMonth, $expiryYear, $cvv, $zip
        ]);

        // 3. Insert items into `order_items`
        if (!empty($cart)) {
            $stmt = $conn->prepare("INSERT INTO order_items (
                order_id, product_name, size, quantity, price
            ) VALUES (?, ?, ?, ?, ?)");
            foreach ($cart as $item) {
                $stmt->execute([
                    $order_id,
                    $item["name"] ?? "",
                    $item["size"] ?? "",
                    $item["quantity"] ?? 1,
                    $item["price"] ?? 0
                ]);
            }
        }

        echo "<script>
            alert('✅ Payment successful! Thank you for your order.');
            localStorage.removeItem('cart');
            window.location.href = 'codeForBothJackets.php';
        </script>";
        exit();

    } catch (PDOException $e) {
        echo "❌ Database error: " . $e->getMessage();
        exit;
    }
}
?>
