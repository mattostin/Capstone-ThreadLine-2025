<?php
session_start();
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Safe default values
$activeUsers = 0;
$topProductLine = "No product views today yet.";
$todayRevenue = 0.00;

// Get active users (last 10 mins)
$result = $conn->query("SELECT COUNT(DISTINCT user_id) as total FROM site_analytics WHERE timestamp >= NOW() - INTERVAL 10 MINUTE");
if ($result && $row = $result->fetch_assoc()) {
  $activeUsers = $row['total'];
}

// Get top product today
$result = $conn->query("
  SELECT p.product_name, COUNT(*) as views
  FROM site_analytics sa
  JOIN products p ON sa.product_id = p.id
  WHERE DATE(sa.timestamp) = CURDATE()
  GROUP BY sa.product_id
  ORDER BY views DESC
  LIMIT 1
");
if ($result && $row = $result->fetch_assoc()) {
  $topProductLine = "Top product today: <strong>{$row['product_name']}</strong> ({$row['views']} views)";
}

// Get today's revenue
$result = $conn->query("
  SELECT SUM(price * quantity) as revenue
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  WHERE DATE(o.order_date) = CURDATE()
");
if ($result && $row = $result->fetch_assoc()) {
  $todayRevenue = $row['revenue'] ?? 0.00;
}

$factLines = [
  "Active users in the last 10 minutes: <strong>$activeUsers</strong>",
  $topProductLine,
  "Today's revenue so far: <strong>$" . number_format($todayRevenue, 2) . "</strong>"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Home - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #232526, #414345);
      color: white;
      padding: 2rem;
      margin: 0;
    }
    h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }
    .summary-box {
      background: rgba(255, 255, 255, 0.07);
      padding: 1.5rem;
      border-radius: 12px;
      margin-top: 2rem;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }
    .summary-box p {
      font-size: 1.2rem;
      line-height: 1.8rem;
    }
    .btn {
      margin-top: 2rem;
      display: inline-block;
      background: #075eb6;
      padding: 0.75rem 1.5rem;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
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

  <!-- Admin Home Snapshot -->
  <main style="text-align: center;">
    <h1>Welcome Back, Admin </h1>
    <p style="font-size: 1.2rem;">Here’s a quick snapshot of your store’s performance today.</p>

    <div class="summary-box">
      <?php foreach ($factLines as $line): ?>
        <p><?= $line ?></p>
      <?php endforeach; ?>
    </div>

    <a href="admin-dashboard.php" class="btn">Go to Full Dashboard</a>
  </main>
</body>
</html>
