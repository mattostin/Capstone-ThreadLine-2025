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
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #075eb6, #88b9e9);
      color: #fff;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
    }

    .logo {
      font-family: 'Lilita One', cursive;
      font-size: 1.8rem;
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
      font-weight: 500;
    }

    .hero {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 4rem 2rem;
      text-align: center;
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
      animation: fadeIn 1s ease-in-out;
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      animation: fadeIn 1.5s ease-in-out;
    }

    .shop-button {
      padding: 1rem 2rem;
      font-size: 1.1rem;
      background: #ffffffcc;
      color: #075eb6;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s ease;
      animation: fadeIn 2s ease-in-out;
    }

    .shop-button:hover {
      background: #fff;
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
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
      <li style="font-weight: bold;">Hi, <?= $username ?></li>
      <li><a href="/php/logout.php">Logout</a></li>
    </ul>
  </nav>

  <section class="hero">
    <h1>Welcome back, <?= $username ?> 👋</h1>
    <p>Discover the latest drops and stay ahead of the fashion game.</p>
    <a href="/php/codeForBothJackets.php" class="shop-button">🛍️ Start Shopping</a>
  </section>
</body>
</html>
