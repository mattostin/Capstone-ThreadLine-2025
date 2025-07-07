<?php
session_start();
$_SESSION['guest'] = true;
header("Location: product.php");
exit;
