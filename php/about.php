<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us | ThreadLine</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: #fff;
      color: #333;
    }
    .container {
      max-width: 900px;
      margin: 4rem auto;
      padding: 0 2rem;
    }
    h1 {
      text-align: center;
      color: #075eb6;
      font-size: 2.5rem;
      margin-bottom: 1rem;
    }
    .section-title {
      font-size: 1.6rem;
      margin: 2rem 0 1rem;
      border-bottom: 2px solid #444;
      padding-bottom: 0.5rem;
    }
    p {
      line-height: 1.8;
    }
  </style>
</head>
<body>
  <div class="page-wrapper">

  <!-- GLOBAL HEADER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <!-- ABOUT CONTENT -->
  <div class="container">
    <h1>About Us</h1>
    <p>
      At ThreadLine, we believe fashion should be as bold and unique as the people who wear it. 
      Founded by creators and designers committed to elevating small brands, ThreadLine offers 
      an e-commerce experience that puts emerging talent at the forefront. Our platform connects 
      customers with stylish, affordable apparel — free from the limits of big-box retail.
    </p>

    <div class="section-title">Our Mission</div>
    <p>
      To empower small fashion businesses with the tools and reach of a modern online storefront, 
      and to offer customers a dynamic space where they can discover unique pieces, responsibly curated.
    </p>

    <div class="section-title">Our Team</div>
    <p>
      We are developers, designers, and fashion lovers who believe that quality, creativity, and community 
      should drive online commerce. Our team works every day to build a system that reflects these values.
    </p>
  </div>

  <!-- GLOBAL FOOTER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</div>

</body>
</html>
