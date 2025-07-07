<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: /php/login.php");
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "USERNAME", "PASSWORD", "DATABASE");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Metrics Queries
$totalOrders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT SUM(total_price) AS revenue FROM orders")->fetch_assoc()['revenue'] ?? 0;
$topProducts = $conn->query("SELECT product_name, SUM(quantity) AS sold FROM order_items GROUP BY product_name ORDER BY sold DESC LIMIT 5");
$activeUsers = $conn->query("SELECT COUNT(*) AS active FROM users WHERE last_activity >= NOW() - INTERVAL 7 DAY")->fetch_assoc()['active'];
$stockLow = $conn->query("SELECT COUNT(*) AS low FROM products WHERE stock < 5")->fetch_assoc()['low'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f7fc;
      margin: 0;
      padding: 2rem;
    }
    .dashboard {
      max-width: 1200px;
      margin: auto;
    }
    h1 {
      text-align: center;
      margin-bottom: 2rem;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }
    .card {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .card h2 {
      font-size: 1.25rem;
      margin-bottom: 0.5rem;
      color: #333;
    }
    .card p {
      font-size: 1.8rem;
      color: #075eb6;
      font-weight: bold;
    }
    .top-products ul {
      list-style: none;
      padding: 0;
    }
    .top-products li {
      margin-bottom: 0.4rem;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1>📊 Admin Dashboard</h1>
    <div class="grid">
      <div class="card">
        <h2>Total Orders</h2>
        <p><?= $totalOrders ?></p>
      </div>
      <div class="card">
        <h2>Total Revenue</h2>
        <p>$<?= number_format($totalRevenue, 2) ?></p>
      </div>
      <div class="card">
        <h2>Active Users (7d)</h2>
        <p><?= $activeUsers ?></p>
      </div>
      <div class="card">
        <h2>Low Inventory Products</h2>
        <p><?= $stockLow ?></p>
      </div>
      <div class="card top-products">
        <h2>Top 5 Products</h2>
        <ul>
          <?php while ($row = $topProducts->fetch_assoc()): ?>
            <li><?= htmlspecialchars($row['product_name']) ?> - <?= $row['sold'] ?> sold</li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>
