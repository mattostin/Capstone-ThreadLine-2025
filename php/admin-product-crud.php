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

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($action === 'add') {
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock, available_sizes, image_front, image_back) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
      "sdisss",
      $_POST['product_name'],
      $_POST['price'],
      $_POST['stock'],
      $_POST['available_sizes'],
      $_POST['image_front'],
      $_POST['image_back']
    );
    $stmt->execute();
    $stmt->close();
    header("Location: codeForBothJackets.php"); // Redirect to product shop
    exit;
  }

  if ($action === 'edit') {
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, available_sizes = ?, image_front = ?, image_back = ? WHERE id = ?");
    $stmt->bind_param(
      "sdisssi",
      $_POST['product_name'],
      $_POST['price'],
      $_POST['stock'],
      $_POST['available_sizes'],
      $_POST['image_front'],
      $_POST['image_back'],
      $_POST['product_id']
    );
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
    echo json_encode(["success" => true]);
    exit;
  }
}

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Product Management</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    body { font-family: 'Poppins', sans-serif; padding: 2rem; background: #f4f4f4; }
    h1 { margin-bottom: 2rem; }
    form, table { background: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; }
    input, textarea { width: 100%; padding: 0.5rem; margin-bottom: 1rem; }
    button { padding: 0.5rem 1rem; background: #075eb6; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .edit-form { margin-top: 1rem; background: #eef; padding: 1rem; border-radius: 6px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 0.75rem; text-align: left; vertical-align: top; }
    td.actions { display: flex; gap: 0.5rem; align-items: center; }
  </style>
</head>
<body>
  <h1>Admin Product Management</h1>

  <form method="POST">
    <h2>Add New Product</h2>
    <input type="text" name="product_name" placeholder="Product Name" required />
    <input type="number" step="0.01" name="price" placeholder="Price" required />
    <input type="number" name="stock" placeholder="Stock Quantity" required />
    <input type="text" name="available_sizes" placeholder="Available Sizes (comma-separated)" required />
    <input type="text" name="image_front" placeholder="Front Image Path" required />
    <input type="text" name="image_back" placeholder="Back Image Path" required />
    <input type="hidden" name="action" value="add" />
    <button type="submit">Add Product</button>
  </form>

  <h2>Existing Products</h2>
  <table>
    <thead>
      <tr>
        <th>Name</th><th>Price</th><th>Stock</th><th>Sizes</th><th>Actions</th>
      </tr>
    </thead>
    <tbody id="product-table-body">
      <?php while ($p = $products->fetch_assoc()): ?>
        <tr id="product-row-<?= $p['id'] ?>">
          <td><?= htmlspecialchars($p['product_name']) ?></td>
          <td>$<?= number_format($p['price'], 2) ?></td>
          <td><?= $p['stock'] ?></td>
          <td><?= htmlspecialchars($p['available_sizes']) ?></td>
          <td class="actions">
            <form method="POST" onsubmit="return confirm('Delete this product?');" class="delete-form">
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>" />
              <button type="submit">Delete</button>
            </form>
            <button onclick="toggleEdit(<?= $p['id'] ?>)">Edit</button>
          </td>
        </tr>
        <tr id="edit-<?= $p['id'] ?>" class="edit-form" style="display:none;">
          <td colspan="5">
            <form method="POST">
              <input type="hidden" name="action" value="edit" />
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>" />
              <input type="text" name="product_name" value="<?= htmlspecialchars($p['product_name']) ?>" />
              <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" />
              <input type="number" name="stock" value="<?= $p['stock'] ?>" />
              <input type="text" name="available_sizes" value="<?= htmlspecialchars($p['available_sizes']) ?>" />
              <input type="text" name="image_front" value="<?= htmlspecialchars($p['image_front']) ?>" />
              <input type="text" name="image_back" value="<?= htmlspecialchars($p['image_back']) ?>" />
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

    // AJAX for delete
    document.querySelectorAll(".delete-form").forEach(form => {
      form.addEventListener("submit", async function(e) {
        e.preventDefault();
        const confirmed = confirm("Are you sure you want to delete this product?");
        if (!confirmed) return;

        const formData = new FormData(form);
        const response = await fetch("admin-product-crud.php", {
          method: "POST",
          body: formData
        });

        const result = await response.json();
        if (result.success) {
          const row = form.closest("tr");
          row.nextElementSibling.remove(); // Remove edit row
          row.remove(); // Remove main row
        } else {
          alert("Failed to delete product.");
        }
      });
    });
  </script>
</body>
</html>
