<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /html/index.html");
    exit;
}

// DB credentials
$servername = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$dbname = "thredqwx_threadline";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total Revenue
$sql = "SELECT SUM(price * quantity) AS total FROM order_items";
$totalRevenue = $conn->query($sql)->fetch_assoc()['total'] ?? 0;

// Orders Today
$sql = "SELECT COUNT(*) AS count FROM orders WHERE DATE(order_date) = CURDATE()";
$ordersToday = $conn->query($sql)->fetch_assoc()['count'] ?? 0;

// Active Users
$sql = "SELECT COUNT(*) AS count FROM users";
$activeUsers = $conn->query($sql)->fetch_assoc()['count'] ?? 0;

// Low Stock Items — temporary static value or hook up to inventory table
$lowStockItems = 7;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - ThreadLine</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom, #075eb6, #88b9e9);
      min-height: 100vh;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 60px;
    }
    h1 {
      font-size: 2.5rem;
      margin-bottom: 2rem;
    }
    .dashboard {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
    }
    .card {
      background: rgba(255,255,255,0.1);
      padding: 2rem 3rem;
      border-radius: 16px;
      text-align: center;
      min-width: 200px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .card h2 {
      font-size: 2rem;
      margin: 0;
    }
    .card p {
      margin-top: 0.5rem;
      font-size: 1rem;
      color: #e0e0e0;
    }
  </style>
</head>
<body>
  <h1>Admin Dashboard</h1>
  <div class="dashboard">
    <div class="card">
      <h2>$<?= number_format($totalRevenue, 2) ?></h2>
      <p>Total Revenue</p>
    </div>
    <div class="card">
      <h2><?= $ordersToday ?></h2>
      <p>Orders Today</p>
    </div>
    <div class="card">
      <h2><?= $activeUsers ?></h2>
      <p>Active Users</p>
    </div>
    <div class="card">
      <h2><?= $lowStockItems ?></h2>
      <p>Low Stock Items</p>
    </div>
  </div>
</body>
</html>
