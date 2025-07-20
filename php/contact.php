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
      background: #000;
      color: #eee;
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
      border-left: 5px solid orange;
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
      outline: 2px solid orange;
    }

    button {
      width: fit-content;
      background-color: orange;
      color: white;
      padding: 0.75rem 1.5rem;
      font-weight: bold;
      font-size: 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e69500;
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
      This time there's no stopping us! Makin' your way in the world today takes everything you’ve got.
      Takin' a break from all your worries sure would help a lot.
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
