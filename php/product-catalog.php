<?php
// DEBUGGING (only for dev/testing - remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

// Connect to DB
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$countResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

// Fetch paginated products
$sql = "SELECT * FROM products ORDER BY position ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ThreadLine | Catalog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- External Styles -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/bootstrap.css">
  <link rel="stylesheet" href="/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />



  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #ffffff 0%, #f2f8ff 100%);
      color: #000;
    }

    .pagination-link {
      margin: 0 0.3rem;
      padding: 0.5rem 1rem;
      background: #075eb6;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }

    .pagination-link.active {
      background: #054a8e;
    }
  </style>
</head>

<body>

  <!-- GLOBAL HEADER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <!-- PRODUCT CATALOG -->
  <div class="container my-5">
<h2 class="text-center" style="margin-top: 2rem; margin-bottom: 2rem; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 2.2rem; color: #075eb6;">
  Product Catalog
</h2>
    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($product = $result->fetch_assoc()): ?>
          <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm">
              <img src="/<?= htmlspecialchars($product['image_front']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['product_name']) ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                <p class="card-text">$<?= number_format($product['price'], 2) ?></p>
                <a href="/php/product.php?id=<?= (int)$product['id'] ?>" class="btn btn-primary">View Product</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No products found.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- PAGINATION -->
  <div class="text-center my-4">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i ?>" class="pagination-link<?= $i === $page ? ' active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>

  <!-- GLOBAL FOOTER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</body>
</html>

<?php $conn->close(); ?>
