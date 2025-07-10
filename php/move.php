<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@threadline.com') {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "thredqwx_admin", "Mostin2003$", "thredqwx_threadline");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id'] ?? 0);
$direction = $_GET['direction'] ?? '';

if ($id > 0 && in_array($direction, ['up', 'down'])) {
  $current = $conn->query("SELECT id, position FROM products WHERE id = $id")->fetch_assoc();
  if (!$current) exit;

  $operator = $direction === 'up' ? '<' : '>';
  $order = $direction === 'up' ? 'DESC' : 'ASC';

  $swap = $conn->query("
    SELECT id, position FROM products 
    WHERE position $operator {$current['position']} 
    ORDER BY position $order LIMIT 1
  ")->fetch_assoc();

  if ($swap) {
    $conn->query("UPDATE products SET position = {$swap['position']} WHERE id = {$current['id']}");
    $conn->query("UPDATE products SET position = {$current['position']} WHERE id = {$swap['id']}");
  }
}

header("Location: admin-product-crud.php");
exit;
