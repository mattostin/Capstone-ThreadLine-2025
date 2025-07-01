<?php
// Start session
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";
$conn = new mysqli($host, $username, $password, $database);

// HTML Header
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ThreadLine | Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<nav class="navbar">
  <div class="logo">ThreadLine</div>
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="codeForBothJackets.html">Shop</a></li>
    <li><a href="signup.php">Signup</a></li>
  </ul>
</nav>
<div class="signup-container">
HTML;

if ($conn->connect_error) {
    die("<h2>❌ Connection failed: " . $conn->connect_error . "</h2></div></body></html>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("<h2>❌ Query error: " . $conn->error . "</h2></div></body></html>");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            echo "<h2>✅ Welcome back, $username!</h2>";
            echo "<p><a href='index.html' style='color: #075eb6; font-weight: bold;'>Go to homepage</a></p>";
        } else {
            echo "<h2>❌ Incorrect password.</h2>";
        }
    } else {
        echo "<h2>❌ No account found with that email.</h2>";
    }

    $stmt->close();
} else {
    echo "<h2>Invalid request.</h2>";
}

$conn->close();

// Footer
echo <<<HTML
</div>
<footer>
  <p>© 2025 ThreadLine. All rights reserved.</p>
</footer>
</body>
</html>
HTML;
?>
