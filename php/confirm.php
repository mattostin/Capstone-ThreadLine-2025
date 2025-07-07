<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation - ThreadLine</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Lilita+One&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #1071977a 0%, #88b9e9 50%, #075eb6 100%);
      margin: 0;
      min-height: 100vh;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: transparent;
    }

    .logo {
      font-family: 'Lilita One', cursive !important;
      font-size: 1.5rem;
      color: white;
      text-decoration: none;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.25rem;
      align-items: center;
      margin: 0;
      padding: 0;
    }

    .nav-links li a,
    .nav-links li span {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      color: white;
      text-decoration: none;
      background: transparent !important;
      border: none;
      padding: 0;
      box-shadow: none;
    }

    .nav-links li a:hover {
      text-decoration: underline;
    }

    .confirmation-container {
      max-width: 800px;
      margin: 4rem auto;
      padding: 2rem;
      background-color: #ffffffdd;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      font-size: 2rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    ul {
      padding: 0;
      list-style: none;
    }

    li {
      margin-bottom: 1rem;
      background: #f3f3f3;
      padding: 1rem;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
    }

    .summary {
      font-size: 1.2rem;
      margin-top: 2rem;
      font-weight: bold;
      text-align: right;
    }

    .confirmation-buttons {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 2.5rem;
    }

    .confirmation-buttons a {
      text-decoration: none;
      background-color: #075eb6;
      color: white;
      padding: 0.65rem 1.25rem;
      border-radius: 8px;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .confirmation-buttons a:hover {
      background-color: #054a8e;
    }
  </style>
</head>
