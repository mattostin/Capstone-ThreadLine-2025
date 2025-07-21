<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ThreadLine | Home</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap&family=Manufacturing+Consent&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/css/style.css" />

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

.hero {
  text-align: center;
  min-height: 80vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 2rem;
  background: url('/images/2597205.jpg') no-repeat center center;
  background-size: cover;
  color: #075eb6;
}


    .hero h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .hero p {
      font-size: 1.25rem;
      margin-bottom: 2rem;
    }

    .hero .btn,
    .hero .btn-secondary {
      display: inline-block;
      background: #fff;
      color: #075eb6;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      max-width: 200px;
      align-self: center; 
      text-decoration: none;
      margin: 0.5rem;
    }

    .hero .btn-secondary {
      background: transparent;
      border: 2px solid white;
      color: white;
    }
  </style>
</head>
<body>

  <!-- HEADER INCLUDE -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <!-- HERO SECTION -->
<section class="hero">
  <h1>ThreadLine</h1>
  <p>Your Brand. Your Rules.</p>
  <a href="/php/product-catalog.php" class="btn">Shop Now</a>
  <a href="/php/login.php" class="btn">Login / Signup</a>
</section>


  <!-- FOOTER INCLUDE -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</body>
</html>
