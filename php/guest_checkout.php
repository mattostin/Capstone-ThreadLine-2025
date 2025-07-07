<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

// Mark the user as a guest
$_SESSION['guest'] = true;

// Redirect to the actual checkout page
header("Location: checkout.php");
exit;
?>
