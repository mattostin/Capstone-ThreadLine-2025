<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@threadline.com') {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($action === 'add') {
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock, available_sizes, image_front, image_back) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $_POST['product_name'], $_POST['price'], $_POST['stock'], $_POST['available_sizes'], $_POST['image_front'], $_POST['image_back']);
    $stmt->execute();
    $stmt->close();
    header("Location: codeForBothJackets.php");
    exit;
  }

  if ($action === 'edit') {
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, available_sizes = ?, image_front = ?, image_back = ? WHERE id = ?");
    $stmt->bind_param("sdisssi", $_POST['product_name'], $_POST['price'], $_POST['stock'], $_POST['available_sizes'], $_POST['image_front'], $_POST['image_back'], $_POST['product_id']);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-product-crud.php");
    exit;
  }

  if ($action === 'delete') {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $_POST['product_id']);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
    exit;
  }
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Product Management</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: #f0f2f5;
    }

    .admin-wrapper {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 250px;
      background: #111827;
      color: white;
      padding: 2rem;
    }

    .main-content {
      flex: 1;
      padding: 2rem;
    }

    .card {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
    }

    h1, h2 {
      font-weight: 600;
      margin-bottom: 1rem;
    }

    form input, form button {
      padding: 0.75rem;
      margin-bottom: 0.75rem;
      font-size: 1rem;
      width: 100%;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    form button {
      background-color: #075eb6;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    form button:hover {
      background-color: #054a8e;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
    }

    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
      vertical-align: top;
    }

    .actions {
      display: flex;
      gap: 0.5rem;
    }

    .actions .delete {
      background-color: #e53e3e;
      color: white;
    }

    .actions .edit {
      background-color: #3182ce;
      color: white;
    }

    .actions button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <?php include 'navber.php'; ?>

  <div class="admin-wrapper">
    <div class="sidebar">
      <h2>Admin Menu</h2>
      <ul>
        <li><a href="admin-dashboard.php" style="color:white">Dashboard</a></li>
        <li><a href="admin-product-crud.php" style="color:white">Manage Products</a></li>
        <li><a href="logout.php" style="color:white">Logout</a></li>
      </ul>
    </div>
    <div class="main-content">
      <div class="card">
        <h1>Admin Product Management</h1>

        <form method="POST">
          <h2>Add New Product</h2>
          <input type="text" name="product_name" placeholder="Product Name" required>
          <input type="number" step="0.01" name="price" placeholder="Price" required>
          <input type="number" name="stock" placeholder="Stock Quantity" required>
          <input type="text" name="available_sizes" placeholder="Available Sizes (comma-separated)" required>
          <input type="text" name="image_front" placeholder="Front Image Path" required>
          <input type="text" name="image_back" placeholder="Back Image Path" required>
          <input type="hidden" name="action" value="add">
          <button type="submit">Add Product</button>
        </form>
      </div>

      <div class="card">
        <h2>Existing Products</h2>
        <table>
          <thead>
            <tr>
              <th>Name</th><th>Price</th><th>Stock</th><th>Sizes</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($p = $products->fetch_assoc()): ?>
              <tr id="product-<?= $p['id'] ?>">
                <td><?= htmlspecialchars($p['product_name']) ?></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><?= htmlspecialchars($p['available_sizes']) ?></td>
                <td class="actions">
                  <form method="POST" class="delete-form" style="display:inline-block">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <button class="delete" type="submit">Delete</button>
                  </form>
                  <button class="edit" onclick="toggleEdit(<?= $p['id'] ?>)">Edit</button>
                </td>
              </tr>
              <tr id="edit-<?= $p['id'] ?>" class="edit-form" style="display:none">
                <td colspan="5">
                  <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <input type="text" name="product_name" value="<?= htmlspecialchars($p['product_name']) ?>" required>
                    <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required>
                    <input type="number" name="stock" value="<?= $p['stock'] ?>" required>
                    <input type="text" name="available_sizes" value="<?= htmlspecialchars($p['available_sizes']) ?>" required>
                    <input type="text" name="image_front" value="<?= htmlspecialchars($p['image_front']) ?>" required>
                    <input type="text" name="image_back" value="<?= htmlspecialchars($p['image_back']) ?>" required>
                    <button type="submit">Save Changes</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleEdit(id) {
      const row = document.getElementById('edit-' + id);
      row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
    }

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', async e => {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this product?')) return;
        const formData = new FormData(form);
        const response = await fetch('admin-product-crud.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();
        if (result.success) {
          const tr = form.closest('tr');
          const editRow = tr.nextElementSibling;
          tr.remove();
          if (editRow && editRow.classList.contains('edit-form')) editRow.remove();
        } else {
          alert('Delete failed');
        }
      });
    });
  </script>
</body>
</html>