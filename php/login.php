<?php
// Secure session and headers
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

date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";
$conn = new mysqli($host, $username, $password, $database);

// ✅ Default redirect for regular users
$redirect = "/php/codeForBothJackets.php";

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
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<nav class="navbar">
  <a class="logo" href="logo_redirect.php">ThreadLine</a>
  <ul class="nav-links">
    <li><a href="../html/index.html">Home</a></li>
    <li><a href="codeForBothJackets.php">Shop</a></li>
HTML;

// ✅ Conditionally show dashboard if admin is already logged in
if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com') {
  echo '<li><a href="admin-dashboard.php">Dashboard</a></li>';
}

echo <<<HTML
    <li><a href="signup.php">Signup</a></li>
  </ul>
</nav>
<div class="signup-container">
HTML;

// Check DB connection
if ($conn->connect_error) {
    die("<h2>❌ Connection failed: " . $conn->connect_error . "</h2></div></body></html>");
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, is_admin FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("<h2>❌ Query error: " . $conn->error . "</h2></div></body></html>");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashed_password, $is_admin);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["is_admin"] = $is_admin;
            $_SESSION["email"] = $email;

            // ✅ Update tracking info
            $now = date('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'];

            $updateSql = "UPDATE users SET last_activity = ?, last_login = ?, last_login_ip = ?, is_logged_in = 1 WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sssi", $now, $now, $ip, $id);
            $updateStmt->execute();
            $updateStmt->close();

            if ($is_admin == 1) {
                header("Location: admin-dashboard.php");
                exit;
            } else {
                header("Location: $redirect");
                exit;
            }
        } else {
            echo "<h2>❌ Invalid email or password.</h2>";
        }
    } else {
        echo "<h2>❌ Invalid email or password.</h2>";
    }

    $stmt->close();
}

$conn->close();

// Show login form
echo <<<HTML
  <h2>Login to ThreadLine</h2>
  <form method="post" action="login.php" style="display: flex; flex-direction: column; gap: 1rem; max-width: 400px; margin: auto;">
    <input type="email" name="email" placeholder="Email" required style="padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;" />
    <input type="password" name="password" placeholder="Password" required style="padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;" />
    <button type="submit" style="padding: 0.75rem; background-color: #075eb6; color: white; border: none; font-weight: bold; font-size: 1rem; border-radius: 6px; cursor: pointer;">Login</button>
  </form>
</div>
</body>
</html>
HTML;
?>
