<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: /php/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile | ThreadLine</title>

  <!-- Fonts and Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="/css/style.css"/>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fff;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1000px;
      margin: 3rem auto;
      padding: 0 2rem;
    }

    h1 {
      font-size: 2.2rem;
      margin-bottom: 1.5rem;
      border-left: 5px solid #075eb6;
      padding-left: 1rem;
    }

    form {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    input {
      padding: 1rem;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      background: #f2f2f2;
      color: #333;
    }

    input:focus {
      outline: 2px solid #00bfff;
    }

    .section {
      margin-bottom: 3rem;
    }

    .full-width {
      grid-column: 1 / -1;
    }

    button {
      padding: 0.8rem 2rem;
      font-size: 1rem;
      border: none;
      background: #075eb6;
      color: white;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      grid-column: 1 / -1;
      justify-self: start;
    }

    button:hover {
      background: #0094cc;
    }
  </style>
</head>
<body>

  <!-- GLOBAL HEADER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <!-- PROFILE CONTENT -->
  <div class="container">
    <h1>My Profile</h1>

    <div class="section">
      <h2>Personal Information</h2>
      <form>
        <input type="text" name="username" placeholder="Username (cannot be changed)" readonly>
        <input type="text" name="firstname" placeholder="First Name">
        <input type="text" name="lastname" placeholder="Last Name">
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="phone" placeholder="Phone Number">
        <input type="text" name="address" placeholder="Address">
        <input type="text" name="zip" placeholder="ZIP Code">
        <input type="text" name="country" placeholder="Country">
        <button type="submit">Update Profile</button>
      </form>
    </div>

    <div class="section">
      <h2>Change Password</h2>
      <form>
        <input type="password" name="old_password" placeholder="Old Password">
        <input type="password" name="new_password" placeholder="New Password">
        <input type="password" name="confirm_password" placeholder="Confirm New Password">
        <button type="submit">Change Password</button>
      </form>
    </div>
  </div>

  <!-- GLOBAL FOOTER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</body>
</html>
