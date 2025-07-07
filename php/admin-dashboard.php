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

// Fetch metrics
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$outOfStock = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock = 0")->fetch_assoc()['total'];
$lowStock = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock BETWEEN 1 AND 2")->fetch_assoc()['total'];
$inventoryValue = $conn->query("SELECT SUM(price * stock) as total FROM products")->fetch_assoc()['total'];

$conn->close();
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
    }

    h1 {
      font-family: 'Lilita One', cursive;
      font-size: 2.5rem;
      margin-bottom: 2rem;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
      text-align: center;
    }

    .card h2 {
      font-size: 2rem;
      margin: 0;
    }

    .card p {
      margin-top: 1rem;
      font-size: 1.1rem;
      color: #ddd;
    }

    a.back-btn {
      color: white;
      text-decoration: underline;
      margin-top: 2rem;
      display: inline-block;
    }
  </style>
</head>
<body>
  <h1>🛠️ Admin Dashboard</h1>

  <div class="dashboard-grid">
    <div class="card">
      <h2><?= $totalProducts ?></h2>
      <p>Total Products</p>
    </div>
    <div class="card">
      <h2><?= $outOfStock ?></h2>
      <p>Out of Stock</p>
    </div>
    <div class="card">
      <h2><?= $lowStock ?></h2>
      <p>Low Stock (1–2)</p>
    </div>
    <div class="card">
      <h2>$<?= number_format($inventoryValue, 2) ?></h2>
      <p>Total Inventory Value</p>
    </div>
  </div>

  <a href="home.php" class="back-btn">← Back to Home</a>
</body>
</html>
