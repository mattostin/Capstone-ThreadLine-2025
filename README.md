This project was developed for the 2025 Capstone class. The idea behind this project is that we wanted to build a ecommerce website like Shopify and Squarespace but not having to have pay for certain features. Shopify and other major online storefronts often require payments to be made to change their site, often as simple as just placement of products. ThreadLine offers full customizability which is easier for new users to understand and to build their own storefront. Everything is here for the host, like the admin dashboard, a way to manage products, and other site options. 

Features:
- User registration & Login
- Admin dashboard
- Login and logout functionality
- Active user tracking
- Responsive frontend design
- Checkout with stock verification

  Live Demo:
  www.Threadline.it.com

  Installation of our Product Instructions:
  1. Clone this repo
  2. Set up a local or web server
  3. Move files to your server's root directory
  4. Update database configuration
    - Edit /php/config.php and set your database credentials:
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPass = 'your_password';

Lastly to Setup the database
1. Create a new MySQL database
2. Import the provided threadline.sql file to intilize the database


Login Credentials
- Admin Account:
- Email: admin@threadline.com
- Password: adminpass

  Usage instructions
  1. Navigate around the homepage to browse all products
  2. Click on products to view them in depth and add to cart
  3. Login or browse as guest and proceed to checkout and complete purchase
  4. Use admin login to:
     -Update product info (stock, images, formatting)
     -View basic website analytics (most viewed product, average time on site duration, and more)
     -Monitor user activity


Customization:
- Product Listings: Use the admin dashboard to effectively add, remove, or update product info
- Styling: modify /css/style.css (working on a page for that so the admin can do it directly in the site)
- Functionality: handled in the backend

Contributers:
Matthew Ostin,
Layan Sayyad, 
Chritian Jennings,
Tomi Akisanya,
