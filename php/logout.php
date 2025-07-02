<?php
session_start();
date_default_timezone_set('America/Los_Angeles');

if (isset($_SESSION['user_id'])) {
    $conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
    $update = $conn->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
    $update->bind_param("i", $_SESSION['user_id']);
    $update->execute();
    $update->close();
    $conn->close();
}

session_unset();
session_destroy();
header("Location: ../html/index.html");
exit();
