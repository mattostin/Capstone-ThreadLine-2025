<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // DB connection settings
  $host = "localhost";
  $username = "thredqwx_admin";
  $password = "Mostin2003$";
  $database = "thredqwx_threadline";

  // Connect to MySQL
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Sanitize inputs
  $name    = $conn->real_escape_string($_POST['name']);
  $phone   = $conn->real_escape_string($_POST['phone']);
  $email   = $conn->real_escape_string($_POST['email']);
  $subject = $conn->real_escape_string($_POST['subject']);
  $message = $conn->real_escape_string($_POST['message']);

  // Insert into database
  $sql = "INSERT INTO contact_requests (name, phone, email, subject, message)
          VALUES ('$name', '$phone', '$email', '$subject', '$message')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Message submitted successfully!'); window.location.href = '/php/contact.php';</script>";
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us | ThreadLine</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #ffffff;
      color: #075eb6;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 3rem auto;
      padding: 0 2rem;
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 1rem;
      border-left: 5px solid #075eb6;
      padding-left: 1rem;
    }

    .contact-intro {
      margin-bottom: 2rem;
      line-height: 1.7;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    input, textarea {
      padding: 1rem;
      border: none;
      background: #f2f2f2;
      border-radius: 4px;
      font-size: 1rem;
      color: #333;
    }

    input:focus, textarea:focus {
      outline: 2px solid #075eb6;
    }

    button {
      width: fit-content;
      background-color: #075eb6;
      color: white;
      padding: 0.75rem 1.5rem;
      font-weight: bold;
      font-size: 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #054a88;
    }
  </style>
</head>
<body>
  <div class="page-wrapper">

  <!-- GLOBAL HEADER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <div class="container">
    <h1>Stay in Touch</h1>
    <p class="contact-intro">
      Have a question or comment for Threadline? We’re here for you! Fill out the form below and we’ll be in touch soon.
    </p>

    <form action="/php/contact.php" method="POST">
      <input type="text" name="name" placeholder="Your Name (required)" required>
      <input type="text" name="phone" placeholder="Phone Number (required)" required>
      <input type="email" name="email" placeholder="Your Email (required)" required>
      <input type="text" name="subject" placeholder="Subject">
      <textarea name="message" rows="5" placeholder="Your Message"></textarea>
      <button type="submit">SEND MESSAGE</button>
    </form>
  </div>

  <!-- GLOBAL FOOTER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</div>

</body>
</html>
