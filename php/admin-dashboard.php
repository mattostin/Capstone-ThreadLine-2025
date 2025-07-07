<?php
session_start();
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Product metrics
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$outOfStock = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock = 0")->fetch_assoc()['total'];
$lowStock = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock BETWEEN 1 AND 2")->fetch_assoc()['total'];
$inventoryValue = $conn->query("SELECT SUM(price * stock) as total FROM products")->fetch_assoc()['total'];

// User metrics
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$loggedInUsers = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_logged_in = 1")->fetch_assoc()['total'];

// Order metrics
$totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$totalRevenue = $conn->query("SELECT SUM(price * quantity) as total FROM order_items")->fetch_assoc()['total'];

// Site analytics
$totalPageviews = $conn->query("SELECT COUNT(*) as total FROM site_analytics")->fetch_assoc()['total'];
$avgSessionTime = $conn->query("SELECT AVG(duration_seconds) as avg FROM site_analytics")->fetch_assoc()['avg'];
$activeUsers24h = $conn->query("
  SELECT COUNT(DISTINCT user_id) as count
  FROM site_analytics
  WHERE timestamp >= NOW() - INTERVAL 1 DAY
")->fetch_assoc()['count'];
$mostViewedProduct = $conn->query("
  SELECT p.product_name, COUNT(*) as views
  FROM site_analytics sa
  JOIN products p ON sa.product_id = p.id
  GROUP BY sa.product_id
  ORDER BY views DESC
  LIMIT 1
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - ThreadLine</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      color: white;
      margin: 0;
      padding: 2rem;
      font-size: 0.95rem;
    }

    h1 {
      font-family: 'Lilita One', cursive;
      font-size: 2rem;
      margin-bottom: 2rem;
    }

    h2.section-title {
      margin-top: 3rem;
      font-size: 1.5rem;
      font-weight: 600;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.1);
      padding: 1.5rem;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      text-align: center;
    }

    .card h2 {
      font-size: 1.8rem;
      margin: 0;
    }

    .card p {
      margin-top: 0.5rem;
      font-size: 0.95rem;
      color: #ddd;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background: rgba(255,255,255,0.08);
      border-radius: 10px;
      overflow: hidden;
    }

    thead {
      background-color: rgba(255,255,255,0.1);
    }

    th, td {
      padding: 0.9rem;
      text-align: left;
      color: white;
      font-size: 0.95rem;
    }

    td {
      border-top: 1px solid rgba(255,255,255,0.08);
    }

    td:last-child, th:last-child {
      text-align: right;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<h1>🛠️ Admin Dashboard</h1>

<h2 class="section-title">📦 Product Stats</h2>
<div class="dashboard-grid">
  <div class="card"><h2><?= $totalProducts ?></h2><p>Total Products</p></div>
  <div class="card"><h2><?= $outOfStock ?></h2><p>Out of Stock</p></div>
  <div class="card"><h2><?= $lowStock ?></h2><p>Low Stock (1–2)</p></div>
  <div class="card"><h2>$<?= number_format($inventoryValue, 2) ?></h2><p>Total Inventory Value</p></div>
</div>

<h2 class="section-title">👤 User Activity</h2>
<div class="dashboard-grid">
  <div class="card"><h2><?= $totalUsers ?></h2><p>Registered Users</p></div>
  <div class="card"><h2><?= $loggedInUsers ?></h2><p>Currently Logged In</p></div>
  <div class="card"><h2><?= $activeUsers24h ?></h2><p>Active in Last 24h</p></div>
</div>

<h2 class="section-title">🧾 Orders & Revenue</h2>
<div class="dashboard-grid">
  <div class="card"><h2><?= $totalOrders ?></h2><p>Total Orders</p></div>
  <div class="card"><h2>$<?= number_format($totalRevenue, 2) ?></h2><p>Total Revenue</p></div>
</div>

<h2 class="section-title">📈 Site Analytics</h2>
<div class="dashboard-grid">
  <div class="card"><h2><?= $totalPageviews ?></h2><p>Total Page Views</p></div>
  <div class="card"><h2><?= round($avgSessionTime, 1) ?>s</h2><p>Avg. Session Duration</p></div>
  <div class="card"><h2><?= $mostViewedProduct['product_name'] ?></h2><p>Most Viewed Product (<?= $mostViewedProduct['views'] ?> views)</p></div>
</div>

<h2 class="section-title">🧵 All Products Overview</h2>
<table>
  <thead>
    <tr>
      <th>Product Name</th>
      <th style="text-align: right;">Price</th>
      <th style="text-align: right;">Stock</th>
      <th style="text-align: right;">Views</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $productResult = $conn->query("
      SELECT 
        p.product_name,
        p.price,
        p.stock,
        (SELECT COUNT(*) FROM site_analytics sa WHERE sa.product_id = p.id) AS views
      FROM products p
    ");
    while ($row = $productResult->fetch_assoc()):
    ?>
      <tr>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td style="text-align: right;">$<?= number_format($row['price'], 2) ?></td>
        <td style="text-align: right;"><?= $row['stock'] ?></td>
        <td style="text-align: right;"><?= $row['views'] ?></td>
      </tr>
    <?php endwhile; $conn->close(); ?>
  </tbody>
</table>

</body>
</html>
