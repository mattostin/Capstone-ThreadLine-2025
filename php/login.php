<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Database config
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

// Handle login
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $user = $conn->real_escape_string($_POST['username']);
  $pass = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username = '$user' OR email = '$user'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      header("Location: /php/product-catalog.php"); // <-- Redirect to product catalog
      exit();
    } else {
      $error = "Invalid password.";
    }
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ThreadLine | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts + Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="/css/style.css" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #68a5cc 0%, #075eb6 100%);
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .login-container {
      max-width: 450px;
      background: #f9f9f9;
      margin: auto;
      margin-top: 4rem;
      margin-bottom: 4rem;
      padding: 2rem 2.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .login-container h2 {
      text-align: center;
      color: #075eb6;
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
    }

    .login-container input {
      width: 100%;
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .login-container button {
      background-color: #075eb6;
      color: white;
      border: none;
      padding: 0.75rem;
      width: 100%;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .login-container button:hover {
      background-color: #054a8e;
    }

    .login-container .link {
      text-align: center;
      margin-top: 1rem;
    }

    .login-container .error {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div class="page-wrapper">


  <!-- GLOBAL HEADER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <!-- LOGIN FORM -->
  <div class="login-container">
    <h2>Login</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="/php/login.php" method="POST">
      <input type="text" name="username" placeholder="Username or Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Log In</button>
    </form>
    <div class="link">
      <p>Don't have an account? <a href="/php/signup.php" style="color: #075eb6; font-weight: bold;">Sign Up</a></p>
    </div>
  </div>

  <!-- GLOBAL FOOTER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</div>

</body>
</html>
