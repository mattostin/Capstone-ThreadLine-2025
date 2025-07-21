<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: /php/login.php");
  exit();
}

$host = "localhost";
$username = "thredqwx_admin";
$password = "Mostin2003$";
$database = "thredqwx_threadline";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname'])) {
  $user_id  = $_SESSION['user_id'];
  $first    = $conn->real_escape_string($_POST['firstname']);
  $last     = $conn->real_escape_string($_POST['lastname']);
  $email    = $conn->real_escape_string($_POST['email']);
  $phone    = $conn->real_escape_string($_POST['phone']);
  $address  = $conn->real_escape_string($_POST['address']);
  $zip      = $conn->real_escape_string($_POST['zip']);
  $country  = $conn->real_escape_string($_POST['country']);

  $update_sql = "UPDATE users SET 
    first_name='$first', 
    last_name='$last', 
    email='$email', 
    phone='$phone', 
    address='$address', 
    zip='$zip', 
    country='$country' 
    WHERE id=$user_id";

  if ($conn->query($update_sql)) {
    $profile_update_success = true;
  } else {
    $profile_update_error = $conn->error;
  }
}

// Fetch current info to pre-fill
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

$conn->close();
?>

<?php if (!empty($profile_update_success)): ?>
  <p style="color: green;">Profile updated successfully.</p>
<?php elseif (!empty($profile_update_error)): ?>
  <p style="color: red;">Error: <?= htmlspecialchars($profile_update_error) ?></p>
<?php endif; ?>


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
<form method="POST" action="">
<input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
<input type="text" name="firstname" value="<?= htmlspecialchars($user['first_name']) ?>">
<input type="text" name="lastname" value="<?= htmlspecialchars($user['last_name']) ?>">
<input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
<input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
<input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">
<input type="text" name="zip" value="<?= htmlspecialchars($user['zip']) ?>">
<input type="text" name="country" value="<?= htmlspecialchars($user['country']) ?>">

        <button type="submit">Update Profile</button>
      </form>
    </div>

    <div class="section">
      <h2>Change Password</h2>
<form method="POST" action="">
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
