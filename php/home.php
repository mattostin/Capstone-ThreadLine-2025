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
      background: linear-gradient(135deg, #88b9e9, #075eb6);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .navbar {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
    }

    .logo {
      font-family: 'Lilita One', cursive;
      font-size: 1.8rem;
      color: white;
      text-decoration: none;
    }

    .nav-links {
      display: flex;
      gap: 1.5rem;
      list-style: none;
    }

    .nav-links a {
      color: white;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color: #d0eaff;
    }

    main {
      margin-top: 8rem;
      background: white;
      padding: 3rem;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 600px;
      width: 90%;
      animation: fadeIn 0.8s ease-out;
    }

    h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #075eb6;
    }

    p {
      font-size: 1.1rem;
      color: #333;
    }

    .shop-button {
      margin-top: 2rem;
      padding: 1rem 2rem;
      background: #075eb6;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      transition: background 0.3s ease;
      display: inline-block;
    }

    .shop-button:hover {
      background: #054f9d;
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
      <li style="color: white; font-weight: bold;">Hi, <?= $username ?></li>
      <li><a href="/php/logout.php">Logout</a></li>
    </ul>
  </nav>

  <main>
    <h1>Welcome back, <?= $username ?>! 👋</h1>
    <p>Ready to shop the latest styles? Browse our featured products below:</p>
    <a href="/php/codeForBothJackets.php" class="shop-button">Go to Shop</a>
  </main>
</body>
</html>
