<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /html/index.html");
    exit;
}
$username = ucfirst(htmlspecialchars($_SESSION['username']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome - ThreadLine</title>

  <!-- Google Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/css/style.css" />

  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      background: linear-gradient(to bottom, #1071977a 0%, #88b9e9 50%, #075eb6 100%);
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      color: white;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.2rem 2rem;
    }

    .logo {
      font-size: 1.8rem;
      color: white;
      text-decoration: none;
      font-weight: 600;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }

    .nav-links li,
    .nav-links li a {
      color: white;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s;
      font-size: 1rem;
    }

    .nav-links li a:hover {
      text-decoration: underline;
    }

    .hero {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 6rem 2rem 3rem;
      animation: fadeIn 1s ease-out;
    }

    .hero h1 {
      font-size: 2.75rem;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .hero p {
      font-size: 1.15rem;
      margin-bottom: 2rem;
      max-width: 600px;
    }

    .shop-button {
      padding: 1rem 2.2rem;
      font-size: 1.1rem;
      background: white;
      color: #075eb6;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      transition: all 0.3s ease;
    }

    .shop-button:hover {
      background: #f0f0f0;
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Secret red pixel link */
    .secret-pixel {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 1px;
      height: 1px;
      background: red;
      z-index: 9999;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <a class="logo" href="/php/logo_redirect.php">ThreadLine</a>
    <ul class="nav-links">
      <li><a href="/php/checkout.php">Checkout</a></li>
      <li><a href="/php/codeForBothJackets.php">Shop</a></li>
      <li>Hi, <?= $username ?></li>
      <li><a href="/php/logout.php">Logout</a></li>
    </ul>
  </nav>

  <section class="hero">
    <h1>Welcome back, <?= $username ?> 👋</h1>
    <p>Check out our latest drops — from clean jackets to everyday comfort. Your style starts here.</p>
    <a href="/php/codeForBothJackets.php" class="shop-button">🛍️ Go to Shop</a>
  </section>

  <!-- Hidden 1px red pixel link to admin dashboard -->
  <a href="/php/admin-dashboard.php" class="secret-pixel" aria-hidden="true" tabindex="-1"></a>
</body>
</html>
