<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php'; // Make sure this file connects to your DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare statement to fetch user
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Get user IP
                function getUserIP() {
                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        return $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
                    } else {
                        return $_SERVER['REMOTE_ADDR'];
                    }
                }
                $user_ip = getUserIP();

                // Set session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;

                // Update login stats
                $update_sql = "UPDATE users SET is_logged_in = 1, last_login = NOW(), last_activity = NOW(), last_login_ip = ? WHERE id = ?";
                if ($update_stmt = $mysqli->prepare($update_sql)) {
                    $update_stmt->bind_param("si", $user_ip, $id);
                    $update_stmt->execute();
                    $update_stmt->close();
                }

                // Redirect to homepage or dashboard
                header("location: index.php");
                exit;
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No account found with that username.";
        }

        $stmt->close();
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
    <form action="login.php" method="post">
        <label>Username</label>
        <input type="text" name="username" required><br>

        <label>Password</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
