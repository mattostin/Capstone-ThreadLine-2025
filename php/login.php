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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// HTML & Navbar
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
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
<nav class="navbar">
  <a class="logo" href="/php/codeForBothJackets.php">ThreadLine</a>
  <ul class="nav-links">
HTML;

if (isset($_SESSION['username'])) {
  echo "<li><a href='checkout.php'>Checkout</a></li>";
  if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com') {
    echo "<li><a href='admin-dashboard.php'>Dashboard</a></li>";
  }
  echo "<li style='color: white; font-weight: bold;'>Hi, " . htmlspecialchars($_SESSION['username']) . "</li>";
  echo "<li><a href='logout.php'>Logout</a></li>";
} else {
  echo <<<HTML
    <li><a href="/html/index.html">Home</a></li>
    <li><a href="/php/codeForBothJackets.php">Shop</a></li>
    <li><a href="/php/login.php">Login</a></li>
    <li><a href="/php/signup.php">Signup</a></li>
HTML;
}

echo <<<HTML
  </ul>
</nav>

<div class="signup-container" style="max-width: 420px; margin: 6rem auto; padding: 2.5rem 2rem;">
  <h2 style="font-family: 'Lilita One', cursive; font-size: 2rem; margin-bottom: 1.5rem; color: #075eb6;">Login to ThreadLine</h2>
  <form method="POST" action="" class="signup-form" style="gap: 1.2rem;">
    <input type="email" name="email" placeholder="Email" required style="padding: 0.9rem;" />
    <input type="password" name="password" placeholder="Password" required style="padding: 0.9rem;" />
    <button type="submit">Login</button>
  </form>
  <p style="margin-top: 1.2rem; font-size: 0.95rem;">Don’t have an account? <a href="signup.php" style="color: #075eb6; font-weight: bold;">Click here to sign up</a></p>
</div>
HTML;

// ✅ Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $username, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;

      $updateStmt = $conn->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
      $updateStmt->bind_param("i", $user_id);
      $updateStmt->execute();
      $updateStmt->close();

      header("Location: codeForBothJackets.php");
      exit;
    } else {
      echo "<p style='color: red; text-align: center;'>Incorrect password. Please try again.</p>";
    }
  } else {
    echo "<p style='color: red; text-align: center;'>No account found with that email.</p>";
  }

  $stmt->close();
}

$conn->close();

echo <<<HTML
<footer>
  <p style="text-align: center; margin-top: 2rem;">© 2025 ThreadLine. All rights reserved.</p>
</footer>
</body>
</html>
HTML;
?>
