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
  <link rel="stylesheet" href="/css/style.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Lilita One', sans-serif;
      background: linear-gradient(to bottom, #1071977a 0%, #88b9e9 50%, #075eb6 100%);
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      color: #fff;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.2rem 2rem;
      background: transparent;
    }

    .logo {
      font-family: 'Lilita One', cursive;
      font-size: 1.6rem;
      color: white;
      text-decoration: none;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }

    .nav-links li a, .nav-links li {
      color: white;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
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
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 2.5rem;
    }

    .shop-button {
      padding: 1rem 2rem;
      font-size: 1.1rem;
      background: white;
      color: #075eb6;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    <p>Check out our newest drops and exclusive styles just for you.</p>
    <a href="/php/codeForBothJackets.php" class="shop-button">🛍️ Go to Shop</a>
  </section>
</body>
</html>
