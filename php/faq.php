<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQs | ThreadLine</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/css/style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: #fff;
      color: #eee;
    }

    .container {
      max-width: 900px;
      margin: 4rem auto;
      padding: 0 2rem;
    }

    h1 {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #075eb6;
    }

    .faq-item {
      margin-bottom: 2rem;
    }

    .faq-item h3 {
      font-size: 1.3rem;
      margin-bottom: 0.5rem;
      color: #075eb6;
    }

    .faq-item p {
      line-height: 1.7;
      color: #000;
    }
  </style>
</head>
<body>

  <!-- GLOBAL HEADER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/header.php'; ?>

  <!-- FAQ CONTENT -->
  <div class="container">
    <h1>Frequently Asked Questions</h1>

    <div class="faq-item">
      <h3>How do I place an order?</h3>
      <p>You can browse products, add them to your cart, and checkout securely with your account or as a guest.</p>
    </div>

    <div class="faq-item">
      <h3>What payment methods do you accept?</h3>
      <p>We accept Visa, Mastercard, PayPal, and Apple Pay. All transactions are securely encrypted.</p>
    </div>

    <div class="faq-item">
      <h3>Can I track my order?</h3>
      <p>Yes, once your order is shipped, you’ll receive a tracking number via email or in your account dashboard.</p>
    </div>

    <div class="faq-item">
      <h3>What is your return policy?</h3>
      <p>Returns are accepted within 14 days of delivery. Items must be unused, with original tags and packaging.</p>
    </div>
  </div>

  <!-- GLOBAL FOOTER -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/php/footer.php'; ?>

</body>
</html>
