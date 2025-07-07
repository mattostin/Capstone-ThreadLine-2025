<?php
session_start();
$_SESSION['guest'] = true;
header("Location: checkout.php");
exit;
