<?php
// Secure session setup
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();
date_default_timezone_set('America/Los_Angeles');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Update is_logged_in = 0 if logged in
if (isset($_SESSION['user_id'])) {
    $conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");

    if ($conn->connect_error) {
        die("❌ DB connection failed: " . $conn->connect_error);
    }

    $update = $conn->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
    $update->bind_param("i", $_SESSION['user_id']);
    $update->execute();
    $update->close();
    $conn->close();
}

// Destroy session
session_unset();
session_destroy();

// Output script to clear cart and redirect
echo '<script>
  localStorage.removeItem("cart");
  window.location.href = "../php/index.php";
</script>';
exit;
?>
