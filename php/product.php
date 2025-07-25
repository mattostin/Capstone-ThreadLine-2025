<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php"; // this provides $conn using PDO

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  echo "Product not found.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['product_name']) ?> - ThreadLine</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    .product-detail-box { max-width: 800px; margin: 5rem auto; padding: 2rem; background: #f4f9ff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Poppins', sans-serif; }
    .product-detail-images { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1rem; }
    .product-detail-images img { max-width: 250px; object-fit: contain; cursor: pointer; }
    .size-selector { margin: 0.5rem 0 1rem; }
    .size-btn { padding: 0.5rem 1rem; margin-right: 0.5rem; border: 1px solid #333; background: white; cursor: pointer; }
    .size-btn.selected { background: #007bff; color: white; }
    #addToCartForm input[type="number"] { width: 60px; padding: 0.3rem; margin-right: 1rem; }
    #addToCartForm button { background: #005bbb; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.8);
    }
    .modal-content {
      margin: auto;
      display: block;
      max-width: 90%;
      max-height: 80vh;
      border-radius: 10px;
    }
    .close {
      position: absolute;
      top: 20px;
      right: 35px;
      color: white;
      font-size: 2rem;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>


  <main>
    <div class="product-detail-box">
      <div class="product-detail-images">
        <img src="/<?= htmlspecialchars($product['image_front']) ?>" alt="Front" data-large="/<?= htmlspecialchars($product['image_front']) ?>">
        <img src="/<?= htmlspecialchars($product['image_back']) ?>" alt="Back" data-large="/<?= htmlspecialchars($product['image_back']) ?>">
      </div>
      <p><?= htmlspecialchars($product['product_name']) ?></p>
      <strong>$<?= number_format($product['price'], 2) ?></strong>

      <form id="addToCartForm">
        <label style="font-size: 1.2rem;">Size:</label>
        <div class="size-selector">
          <?php foreach (explode(',', $product['available_sizes']) as $size): ?>
            <button type="button" class="size-btn" data-size="<?= $size ?>"><?= strtoupper($size) ?></button>
          <?php endforeach; ?>
        </div>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" value="1" min="1" />
        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </main>

  <!-- Image Modal -->
  <div id="imgModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImg" />
  </div>

  <script>
    // Cart logic
    let selectedSize = "";
    document.querySelectorAll('.size-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedSize = btn.dataset.size;
      });
    });

    document.getElementById('addToCartForm').addEventListener('submit', e => {
      e.preventDefault();
      const quantity = parseInt(document.getElementById('quantity').value);
      if (!selectedSize) {
        alert("Please select a size.");
        return;
      }

      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const existingIndex = cart.findIndex(item => item.id === <?= $product['id'] ?> && item.size === selectedSize);

      if (existingIndex > -1) {
        cart[existingIndex].quantity += quantity;
      } else {
        cart.push({
          id: <?= $product['id'] ?>,
          name: <?= json_encode($product['product_name']) ?>,
          price: <?= $product['price'] ?>,
          quantity,
          size: selectedSize
        });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      alert("<?= $product['product_name']?>added to cart!");
    });

    // Modal logic
    const modal = document.getElementById("imgModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close");

    document.querySelectorAll(".product-detail-images img").forEach(img => {
      img.addEventListener("click", () => {
        modal.style.display = "block";
        modalImg.src = img.dataset.large;
      });
    });

    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = e => { if (e.target === modal) modal.style.display = "none"; };
  </script>

  <!-- ✅ TRACK VIEW SCRIPT -->
  <script>
    const startTime = Date.now();

    window.addEventListener("beforeunload", function () {
      const endTime = Date.now();
      const duration = Math.round((endTime - startTime) / 1000);

      navigator.sendBeacon("/php/track_view.php", JSON.stringify({
        user_id: <?= isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'null' ?>,
        page_visited: window.location.pathname,
        product_id: <?= $productId ?>,
        session_start: new Date(startTime).toISOString().slice(0, 19).replace("T", " "),
        session_end: new Date(endTime).toISOString().slice(0, 19).replace("T", " "),
        duration_seconds: duration
      }));
    });
  </script>
</body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>


</html>
