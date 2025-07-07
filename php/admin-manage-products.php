<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@threadline.com') {
  header("Location: login.php");
  exit;
}

$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $name = $_POST['product_name'];
  $price = (float)$_POST['price'];
  $stock = (int)$_POST['stock'];
  $sizes = $_POST['available_sizes'];
  $imageFront = $_POST['image_front'];
  $imageBack = $_POST['image_back'];

  if ($_POST['action'] === 'add') {
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock, available_sizes, image_front, image_back) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $name, $price, $stock, $sizes, $imageFront, $imageBack);
    $stmt->execute();
    $stmt->close();
  }

  if ($_POST['action'] === 'edit') {
    $id = (int)$_POST['product_id'];
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, available_sizes = ?, image_front = ?, image_back = ? WHERE id = ?");
    $stmt->bind_param("sdisssi", $name, $price, $stock, $sizes, $imageFront, $imageBack, $id);
    $stmt->execute();
    $stmt->close();
  }

  if ($_POST['action'] === 'delete') {
    $id = (int)$_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
  }

  header("Location: admin-manage-products.php");
  exit;
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products - Admin</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { font-family: 'Poppins', sans-serif; padding: 2rem; background: #f4f4f4; }
    h1 { margin-bottom: 2rem; }
    form, table { background: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; }
    input, textarea { width: 100%; padding: 0.5rem; margin-bottom: 1rem; }
    button { padding: 0.5rem 1rem; background: #075eb6; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .edit-form { margin-top: 1rem; background: #eef; padding: 1rem; border-radius: 6px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 0.75rem; text-align: left; }
    td:last-child { display: flex; gap: 0.5rem; }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>
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

  <h2>Existing Products</h2>
  <table>
    <thead>
      <tr>
        <th>Name</th><th>Price</th><th>Stock</th><th>Sizes</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($p = $products->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($p['product_name']) ?></td>
          <td>$<?= number_format($p['price'], 2) ?></td>
          <td><?= $p['stock'] ?></td>
          <td><?= htmlspecialchars($p['available_sizes']) ?></td>
          <td>
            <form method="POST" style="display:inline-block">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <button type="submit" onclick="return confirm('Delete this product?')">Delete</button>
            </form>
            <button onclick="toggleEdit(<?= $p['id'] ?>)">Edit</button>
          </td>
        </tr>
        <tr id="edit-<?= $p['id'] ?>" class="edit-form" style="display:none;">
          <td colspan="5">
            <form method="POST">
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <input type="text" name="product_name" value="<?= htmlspecialchars($p['product_name']) ?>">
              <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>">
              <input type="number" name="stock" value="<?= $p['stock'] ?>">
              <input type="text" name="available_sizes" value="<?= htmlspecialchars($p['available_sizes']) ?>">
              <input type="text" name="image_front" value="<?= htmlspecialchars($p['image_front']) ?>">
              <input type="text" name="image_back" value="<?= htmlspecialchars($p['image_back']) ?>">
              <button type="submit">Save Changes</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <script>
    function toggleEdit(id) {
      const row = document.getElementById('edit-' + id);
      row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
    }
  </script>
</body>
</html>
