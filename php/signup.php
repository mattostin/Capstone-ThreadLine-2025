<?php
// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $database);

// HTML header
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ThreadLine | Sign Up</title>
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
    <li><a href="login.html">Login</a></li>
    <li><a href="signup.html">Signup</a></li>
  </ul>
</nav>
<div class="signup-container">
HTML;

// Check connection
if ($conn->connect_error) {
  die("<h2>❌ Connection failed: " . $conn->connect_error . "</h2></div></body></html>");
}

// Handle form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $first_name = $_POST['first_name'];
  $last_name  = $_POST['last_name'];
  $username   = $_POST['username'];
  $email      = $_POST['email'];
  $dob        = $_POST['dob'];
  $password   = $_POST['password'];

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (first_name, last_name, username, email, dob, password)
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("<h2>❌ Prepare failed: " . $conn->error . "</h2></div></body></html>");
  }

  $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $dob, $hashed_password);

  if ($stmt->execute()) {
    echo "<h2>✅ Account Created Successfully</h2>";
    echo "<p><a href='login.html' style='color: #075eb6; font-weight: bold;'>Click here to log in</a></p>";
  } else {
    echo "<h2>❌ Error: " . $stmt->error . "</h2>";
  }

  $stmt->close();
}

$conn->close();

echo <<<HTML
</div>
<footer>
  <p>© 2025 ThreadLine. All rights reserved.</p>
</footer>
</body>
</html>
HTML;
?>
