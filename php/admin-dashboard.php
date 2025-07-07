<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /html/index.html");
    exit;
}
$username = ucfirst(htmlspecialchars($_SESSION['username']));

// (Optional) Check if user is admin (if you store roles in DB)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - ThreadLine</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/css/style.css" />
  <style>
    body {
      margin: 0;
      background: linear-gradient(to bottom, #075eb6, #88b9e9);
      min-height: 100vh;
      color: white;
      font-family: 'Poppins', sans-serif;
    }

    .dashboard {
      padding: 2rem;
    }

    h1 {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 2rem;
    }

    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .metric-card {
      background-color: #ffffff22;
      padding: 1.5rem;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .metric-card h2 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    .metric-card p {
      font-size: 1rem;
      color: #eee;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1>Admin Dashboard</h1>
    <div class="metrics-grid">
      <div class="metric-card">
        <h2>$12,340</h2>
        <p>Total Revenue</p>
      </div>
      <div class="metric-card">
        <h2>58</h2>
        <p>Orders Today</p>
      </div>
      <div class="metric-card">
        <h2>124</h2>
        <p>Active Users</p>
      </div>
      <div class="metric-card">
        <h2>7</h2>
        <p>Low Stock Items</p>
      </div>
    </div>
  </div>
</body>
</html>
