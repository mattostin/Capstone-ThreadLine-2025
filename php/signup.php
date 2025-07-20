<?php

$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $first_name = $conn->real_escape_string($_POST['first_name']);
  $last_name = $conn->real_escape_string($_POST['last_name']);
  $username_input = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $password_raw = $_POST['password'];
  $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

  // Check if username or email already exists
  $check = $conn->query("SELECT * FROM users WHERE username = '$username_input' OR email = '$email'");
  if ($check && $check->num_rows > 0) {
    $error = "Username or email already taken.";
  } else {
    $insert = $conn->query("INSERT INTO users (first_name, last_name, username, email, password) VALUES ('$first_name', '$last_name', '$username_input', '$email', '$password_hashed')");
    if ($insert) {
      $success = "Account created successfully. <a href='/login.php' style='color:white; text-decoration:underline;'>Log in now</a>.";
    } else {
      $error = "Something went wrong. Please try again.";
    }
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ThreadLine | Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
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

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .navbar a {
      color: #075eb6;
      font-weight: bold;
      text-decoration: none;
      margin-left: 1.5rem;
    }

    .signup-box {
      background: #f5f5f5;
      max-width: 400px;
      margin: auto;
      margin-top: 4rem;
      margin-bottom: 4rem;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }

    .signup-box h2 {
      margin-bottom: 1.5rem;
      color: #075eb6;
    }

    .signup-box input {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .signup-box button {
      background-color: #075eb6;
      color: white;
      border: none;
      padding: 0.75rem;
      width: 100%;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .signup-box button:hover {
      background-color: #054a8e;
    }

    .message {
      margin-bottom: 1rem;
      padding: 0.75rem;
      border-radius: 6px;
      font-weight: bold;
    }

    .error {
      background-color: #ffd1d1;
      color: #a40000;
    }

    .success {
      background-color: #c9f7c5;
      color: #1a5900;
    }

    footer {
      margin-top: auto;
      background: linear-gradient(to top, #075eb6, #107197);
      color: white;
      text-align: center;
      padding: 1rem;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <div class="page-wrapper">

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>
    

  <!-- SIGNUP FORM -->
  <div class="signup-box">
    <h2>Create Your Account</h2>

    <?php if ($error): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="message success"><?= $success ?></div>
    <?php endif; ?>

    <form action="signup.php" method="POST">
      <input type="text" name="first_name" placeholder="First Name" required />
      <input type="text" name="last_name" placeholder="Last Name" required />
      <input type="text" name="username" placeholder="Username" required />
      <input type="email" name="email" placeholder="Email Address" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Sign Up</button>
    </form>
    <p style="margin-top: 1rem;">Already have an account? <a href="/login.php" style="color: #075eb6; font-weight: bold;">Log In</a></p>
  </div>

  <!-- FOOTER -->
  <footer class="threadline-footer">
    <div class="footer-top">
      <div class="breadcrumbs">
        <a href="/index.html">Home</a> / 
        <a href="/about.html">Our Story</a> / 
        <a href="/php/products-catalog.php">Shop</a> / 
        <a href="/faq.html">Frequently Asked Questions</a> / 
        <a href="/contact.html">Contact</a>
      </div>
    </div>

    <div class="footer-content">
      <div class="footer-social">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
        <a href="#"><i class="fab fa-google-plus-g"></i></a>
      </div>

      <div class="footer-description">
        <p>On the other hand, we denounce with righteous indignation and dislike those who are beguiled by the charms of pleasure and demoralized by indulgence.</p>
      </div>

      <div class="footer-contacts">
        <p><strong>Email:</strong> info@threadline.com</p>
        <p><strong>Tel:</strong> +1 (555) 123-4567</p>
        <p><strong>Address:</strong> 52 Web Avenue, Washington DC, 20001</p>
      </div>

      <div class="footer-rights">
        <p>&copy; 2025 ThreadLine. All rights reserved.</p>
      </div>
    </div>
  </footer>


  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</div>


</body>
</html>
