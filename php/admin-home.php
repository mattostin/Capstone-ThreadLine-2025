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
$quotes = [
  "Success is not for the lazy 💼",
  "You're not managing a site — you're running a kingdom 👑",
  "Every click is cash. Every view is power 💡",
  "ThreadLine’s growth = Your hustle 📈",
  "Great admins run smooth ships 🚢"
];
$leaderboard = [
  ["name" => "You", "sales" => "$1,224"],
  ["name" => "AutoBot 2", "sales" => "$912"],
  ["name" => "User23", "sales" => "$410"],
];

$fact = $adminFacts[array_rand($adminFacts)];
$quote = $quotes[array_rand($quotes)];
$deal = ["Green Shorts - 40% OFF!", "White Jacket - Buy 1 Get 1 Free!", "Free shipping over $20!"];
$dailyDeal = $deal[array_rand($deal)];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome Admin - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #1f4037, #99f2c8);
      color: white;
      margin: 0;
      padding: 2rem;
      text-align: center;
    }
    h1 {
      font-size: 2.8rem;
      font-family: 'Lilita One', cursive;
      margin-bottom: 0.5rem;
    }
    .fact, .quote {
      margin-top: 1rem;
      font-size: 1.3rem;
      background: rgba(255,255,255,0.15);
      padding: 1rem 2rem;
      border-radius: 10px;
      display: inline-block;
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
      cursor: pointer;
    }
    .deal-box {
      margin-top: 2rem;
      background: #fff3;
      border-radius: 12px;
      padding: 1.2rem;
      font-size: 1.2rem;
      display: inline-block;
    }
    .leaderboard {
      margin-top: 3rem;
      text-align: left;
      max-width: 400px;
      margin-left: auto;
      margin-right: auto;
    }
    .leaderboard h3 {
      margin-bottom: 0.5rem;
    }
    .leaderboard li {
      background: rgba(255,255,255,0.1);
      padding: 0.75rem;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      display: flex;
      justify-content: space-between;
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
<p class="quote">💬 <?= $quote ?></p>
<p class="fact">🔥 <?= $fact ?></p>

<div class="deal-box">
  🤑 <strong>Deal of the Day:</strong> <?= $dailyDeal ?>
</div>

<div class="leaderboard">
  <h3>🏆 Weekly Sales Leaderboard</h3>
  <ul>
    <?php foreach ($leaderboard as $entry): ?>
      <li><span><?= $entry['name'] ?></span><span><?= $entry['sales'] ?></span></li>
    <?php endforeach; ?>
  </ul>
</div>

<button class="btn" onclick="celebrate()">Celebrate</button>
<a href="admin-dashboard.php" class="btn">Go to Dashboard</a>

<script>
function celebrate() {
  confetti({
    particleCount: 150,
    spread: 100,
    origin: { y: 0.6 }
  });
  const audio = new Audio('https://www.myinstants.com/media/sounds/taco-bell-bong.mp3');
  audio.play();
}
</script>
</body>
</html>
