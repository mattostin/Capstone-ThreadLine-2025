<?php
session_start();

if (isset($_SESSION['username'])) {
    // If logged in, go to personalized homepage
    header("Location: /php/home.php");
} else {
    // If not logged in, go to static landing page
    header("Location: /html/index.html");
}
exit;
