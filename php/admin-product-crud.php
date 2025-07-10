<?php
session_start();//

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@threadline.com') {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($action === 'add') {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $sizes = $_POST['available_sizes'];

    $frontImgName = basename($_FILES['image_front']['name']);
    $frontTarget = '../uploads/' . $frontImgName;
    move_uploaded_file($_FILES['image_front']['tmp_name'], $frontTarget);

    $backImgName = basename($_FILES['image_back']['name']);
    $backTarget = '../uploads/' . $backImgName;
    move_uploaded_file($_FILES['image_back']['tmp_name'], $backTarget);

    $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock, available_sizes, image_front, image_back) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $productName, $price, $stock, $sizes, $frontTarget, $backTarget);
    $stmt->execute();
    $stmt->close();
    header("Location: ../php/admin-product-crud.php");
    exit;
  }

  if ($action === 'edit') {
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, available_sizes = ?, image_front = ?, image_back = ? WHERE id = ?");
    $stmt->bind_param("sdisssi", $_POST['product_name'], $_POST['price'], $_POST['stock'], $_POST['available_sizes'], $_POST['image_front'], $_POST['image_back'], $_POST['product_id']);
    $stmt->execute();
    $stmt->close();
    header("Location: ../php/admin-product-crud.php");
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
  <style>
    body {
      background: linear-gradient(to bottom, #0f2027, #203a43, #2c5364);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1100px;
      margin: 3rem auto;
      padding: 2rem;
      background-color: #1e1e1e;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .admin-card {
      margin-bottom: 2rem;
    }

    h1, h2 {
      margin-bottom: 1rem;
    }

    .admin-form input[type="text"],
    .admin-form input[type="number"],
    .admin-form input[type="file"],
    .edit-form input[type="text"],
    .edit-form input[type="number"] {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 0.75rem;
      border: 1px solid #444;
      border-radius: 6px;
      background-color: #2a2a2a;
      color: #fff;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
      padding: 0.6rem 1.2rem;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-primary { background-color: #0a67a3; color: white; }
    .btn-secondary { background-color: #555; color: white; }
    .btn-danger { background-color: #c0392b; color: white; }

    table.admin-table {
      width: 100%;
      border-collapse: collapse;
      background-color: #2a2a2a;
      color: white;
    }

    table.admin-table th,
    table.admin-table td {
      padding: 1rem;
      border: 1px solid #444;
    }

    table.admin-table th {
      background-color: #111;
    }
  </style>
</head>
<body class="admin-page">

<?php include '../php/navbar.php'; ?>

<div class="container">
  <div class="admin-card">
    <h1>Admin Product Management</h1>

    <form method="POST" enctype="multipart/form-data" class="admin-form">
      <h2>Add New Product</h2>
      <input type="text" name="product_name" placeholder="Product Name" required>
      <input type="number" step="0.01" name="price" placeholder="Price" required>
      <input type="number" name="stock" placeholder="Stock Quantity" required>
      <input type="text" name="available_sizes" placeholder="Available Sizes (comma-separated)" required>
      <input type="file" name="image_front" accept="image/*" required>
      <input type="file" name="image_back" accept="image/*" required>
      <input type="hidden" name="action" value="add">
      <button type="submit" class="btn-primary">Add Product</button>
    </form>
  </div>

  <div class="admin-card">
    <h2>Existing Products</h2>
    <table class="admin-table">
      <thead>
        <tr><th>Name</th><th>Price</th><th>Stock</th><th>Sizes</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php while ($p = $products->fetch_assoc()): ?>
          <tr id="product-<?= $p['id'] ?>">
            <td><?= htmlspecialchars($p['product_name']) ?></td>
            <td>$<?= number_format($p['price'], 2) ?></td>
            <td><?= $p['stock'] ?></td>
            <td><?= htmlspecialchars($p['available_sizes']) ?></td>
            <td class="actions">
              <form method="POST" class="delete-form" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <button class="btn-danger" type="submit">Delete</button>
              </form>
              <button class="btn-secondary" onclick="toggleEdit(<?= $p['id'] ?>)">Edit</button>
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
                <button type="submit" class="btn-primary">Save Changes</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
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
    const response = await fetch('../php/admin-product-crud.php', {
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
