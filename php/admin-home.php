<?php
session_start();

// Redirect non-admins
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: codeForBothJackets.php");
    exit;
}

// Fun rotating facts
$facts = [
    "Did you know? Adding reviews can boost conversion by 270%.",
    "Tip: 93% of online experiences start with a search engine.",
    "Admins who A/B test earn 30% more in sales on average.",
    "Fun Fact: Mobile users account for over 50% of traffic!",
    "Analytics show most sales happen on Fridays!",
    "Offering free shipping increases sales by 20%.",
    "Bright colors can improve button click rates by 45%.",
    "Upselling increases average order value by 10–30%."
];

$randomFact = $facts[array_rand($facts)];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Home – ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .admin-message {
      max-width: 700px;
      margin: 5rem auto;
      background-color: #ffffffdd;
      padding: 2rem;
      text-align: center;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .admin-message h1 {
      font-size: 2rem;
      color: #075eb6;
      margin-bottom: 1rem;
    }

    .admin-message p {
      font-size: 1.2rem;
    }

    .fact {
      margin-top: 2rem;
      font-style: italic;
      color: #333;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="admin-home.php" class="logo">ThreadLine Admin</a>
    <ul class="nav-links">
      <li><a href="admin-dashboard.php">Dashboard</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </header>

  <div class="admin-message">
    <h1>Good job, Admin 👑</h1>
    <p>You've been absolutely crushing it with the sales lately. Keep up the great work!</p>

    <div class="fact">
      <strong>💡 Insight:</strong> <?= $randomFact ?>
    </div>
  </div>
</body>
</html>
