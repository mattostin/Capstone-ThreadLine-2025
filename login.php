<?php
session_start();

// DEBUG: Enable for development only
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$db   = 'thredqwx_threadline';
$user = 'thredqwx_admin';
$pass = 'Mostin2003$';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Only proceed if both fields are filled
    if ($username && $password) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username_db, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Get user IP
                    function getUserIP() {
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
                        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
                        return $_SERVER['REMOTE_ADDR'];
                    }
                    $user_ip = getUserIP();

                    // Set session
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username_db;

                    // Update last login/activity/IP
                    $update_sql = "UPDATE users SET is_logged_in = 1, last_login = NOW(), last_activity = NOW(), last_login_ip = ? WHERE id = ?";
                    if ($update_stmt = $mysqli->prepare($update_sql)) {
                        $update_stmt->bind_param("si", $user_ip, $id);
                        $update_stmt->execute();
                        $update_stmt->close();
                    }

                    header("location: index.php");
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "No account found with that username.";
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in both fields.";
    }
}

$mysqli->close();
?>

<!-- Simple login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ThreadLine</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="login.php" method="post">
        <label>Username</label>
        <input type="text" name="username" required><br>

        <label>Password</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
