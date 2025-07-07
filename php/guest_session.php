<?php
session_start();
$_SESSION['guest'] = true;

$redirect = isset($_GET['redirect']) && $_GET['redirect'] === 'shop' 
  ? '/php/codeForBothJackets.php' 
  : '/php/checkout.php';

header("Location: $redirect");
exit;
