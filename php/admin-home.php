<?php
session_start();

$adminFacts = [
  "🧠 70% of carts are abandoned — but not yours!",
  "💰 You’ve generated over $1,000 in revenue this month.",
  "🚀 Site visits increased 25% last week!",
  "📦 Your top-selling item is flying off the shelves!",
  "🌎 Customers from 5+ countries have visited your store.",
  "🔒 100% uptime this month — site’s running smoothly!"
];
$fact = $adminFacts[array_rand($adminFacts)];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome Admin - ThreadLine</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #232526, #414345);
      color: white;
      margin: 0;
      padding: 2rem;
      text-align: center;
    }
    h1 {
      font-size: 2.5rem;
      font-family: 'Lilita One', cursive;
      margin-bottom: 1rem;
    }
    .fact {
      margin-top: 2rem;
      font-size: 1.4rem;
      background: rgba(255,255,255,0.1);
      display: inline-block;
      padding: 1rem 2rem;
      border-radius: 10px;
      animation: rotate 6s linear infinite;
    }
    @keyframes rotate {
      0% { transform: rotateX(0deg); }
      100% { transform: rotateX(360deg); }
    }
    .btn {
      display: inline-block;
      margin-top: 2rem;
      padding: 0.75rem 1.5rem;
      background: #075eb6;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 6px;
    }
  </style>
</head>
<body>
<nav class="navbar">
  <a class="logo" href="logo_redirect.php">ThreadLine</a>
  <ul class="nav-links">
    <li><a href="codeForBothJackets.php">Shop</a></li>
    <li><a href="checkout.php">Checkout</a></li>
    <?php if (isset($_SESSION['username'])): ?>
      <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@threadline.com'): ?>
        <li><a href="admin-dashboard.php">Dashboard</a></li>
      <?php endif; ?>
      <li style="color: white; font-weight: bold;">Hi, <?= ucfirst(htmlspecialchars($_SESSION['username'])) ?></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="signup.php">Signup</a></li>
    <?php endif; ?>
  </ul>
</nav>

<h1>Welcome Back, Admin 👑</h1>
<p style="font-size: 1.2rem;">You're doing amazing. Keep up the great work managing ThreadLine.</p>
<div class="fact"><?= $fact ?></div>
<a href="admin-dashboard.php" class="btn">Go to Dashboard</a>
</body>
</html>
