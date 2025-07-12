<?php
// ✅ Secure session and headers
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
session_start();

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("X-XSS-Protection: 1; mode=block");

// Enable error reporting
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
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
<nav class="navbar">
  <div class="logo">ThreadLine</div>
  <ul class="nav-links">
    <li><a href="/html/index.html">Home</a></li>
    <li><a href="/php/codeForBothJackets.php">Shop</a></li>
HTML;

if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com') {
  echo '<li><a href="/php/admin-dashboard.php">Dashboard</a></li>';
}

echo <<<HTML
    <li><a href="/php/login.php">Login</a></li>
    <li><a href="/php/signup.php">Signup</a></li>
  </ul>
</nav>
<div class="signup-container">
  <form method="POST" action="">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required /><br><br>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required /><br><br>

    <label for="username">Username:</label>
    <input type="text" name="username" required /><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" required /><br><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" name="dob" required /><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required /><br><br>

    <button type="submit">Sign Up</button>
  </form>
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
    echo "<p><a href='login.php' style='color: #075eb6; font-weight: bold;'>Click here to log in</a></p>";
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
