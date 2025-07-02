<?php
session_start();

// Enable error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection
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
  <a class="logo" href="index.html">ThreadLine</a>
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="codeForBothJackets.php">Shop</a></li>
    <li><a href="signup.php">Signup</a></li>
  </ul>
</nav>
<div class="signup-container">
HTML;

// Check DB connection
if ($conn->connect_error) {
    die("<h2>❌ Connection failed: " . $conn->connect_error . "</h2></div></body></html>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        echo "<h2 style='color:red;'>❌ Please fill in both fields.</h2>";
    } else {
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $username, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Valid login — set session
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;

                    // Capture IP
                    $ip = $_SERVER['HTTP_CLIENT_IP'] ??
                          $_SERVER['HTTP_X_FORWARDED_FOR'] ??
                          $_SERVER['REMOTE_ADDR'];

                    $now = date('Y-m-d H:i:s');
                    $update = $conn->prepare("UPDATE users SET is_logged_in = 1, last_activity = ?, last_login_ip = ? WHERE id = ?");
                    $update->bind_param("ssi", $now, $ip, $id);
                    $update->execute();

                    // Redirect
                    header("Location: codeForBothJackets.php");
                    exit();
                } else {
                    echo "<h2 style='color:red;'>❌ Incorrect password.</h2>";
                }
            } else {
                echo "<h2 style='color:red;'>❌ No account found with that email.</h2>";
            }

            $stmt->close();
        } else {
            echo "<h2 style='color:red;'>❌ Query error: " . $conn->error . "</h2>";
        }
    }
} else {
    // Show login form
    echo <<<FORM
    <h2>Login</h2>
    <form class="signup-form" action="login.php" method="POST">
      <input type="email" name="email" placeholder="Email Address" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <p style="margin-top: 1rem;">
      Don't have an account? <a href="signup.php" style="color: #075eb6; font-weight: bold;">Sign Up</a>
    </p>
FORM;
}

$conn->close();

// HTML Footer
echo <<<HTML
</div>
<footer>
  <p>© 2025 ThreadLine. All rights reserved.</p>
</footer>
</body>
</html>
HTML;
?>
