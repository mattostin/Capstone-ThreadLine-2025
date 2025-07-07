<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Session timeout: 30 minutes
if (!isset($_SESSION['LAST_ACTIVITY'])) {
  $_SESSION['LAST_ACTIVITY'] = time();
} elseif (time() - $_SESSION['LAST_ACTIVITY'] > 1800) {
  session_unset();
  session_destroy();
  header("Location: login.php?redirect=checkout.php");
  exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// 🚨 Block access unless logged in OR guest session
if (!isset($_SESSION['user_id']) && !isset($_SESSION['guest'])) {
  header("Location: login.php?redirect=checkout.php");
  exit;
}

// ✅ Update last_activity in DB if logged in (not for guest)
if (isset($_SESSION['user_id'])) {
  date_default_timezone_set('America/Los_Angeles');
  $conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");

  if (!$conn->connect_error) {
    $now = date('Y-m-d H:i:s');
    $updateSql = "UPDATE users SET last_activity = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $now, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }
}
?>
