EcomAPI - Laravel E-commerce API
Overview
EcomAPI is a RESTful API built with Laravel, designed to power the backend of an e-commerce platform. It supports product management, shopping cart operations, order processing, and integrates with Razorpay for payment handling.

Features
Product Management: CRUD operations for products

Shopping Cart: Add, update, and remove items

Checkout & Payment: Integration with Razorpay for payment processing

Order Management: View and update order statuses

Admin Panel: Access to all orders and their details

Requirements
PHP >= 8.0

Composer

MySQL or compatible database

Node.js (for frontend development)

NPM (for frontend dependencies)

Installation
1. Clone the Repository
bash
Copy
Edit
git clone https://github.com/shaikhzaid01/EcomAPI.git
cd EcomAPI
2. Install Backend Dependencies
bash
Copy
Edit
composer install
3. Set Up Environment Variables
Copy the example environment file and edit it:

bash
Copy
Edit
cp .env.example .env
Update the .env file with your database and Razorpay credentials:

env
Copy
Edit
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret
4. Generate Application Key
bash
Copy
Edit
php artisan key:generate
5. Run Migrations and Seeders
bash
Copy
Edit
php artisan migrate --seed
6. Serve the Application
bash
Copy
Edit
php artisan serve
The application will be accessible at http://127.0.0.1:8000.

API Documentation
Base URL
arduino
Copy
Edit
http://127.0.0.1:8000/api/v1
Endpoints
Products
GET /products: List all products

POST /products: Create a new product

GET /products/{id}: Get product details

PUT /products/{id}: Update product

DELETE /products/{id}: Delete product

Cart
POST /cart: Add item to cart

GET /cart: List cart items

PUT /cart/{id}: Update cart item

DELETE /cart/{id}: Remove item from cart

Checkout & Payment
POST /checkout: Initiate checkout and payment

POST /razorpay/verify: Verify Razorpay payment

Orders (Admin)
GET /orders: List all orders

GET /orders/{id}: Get order details

PUT /orders/{id}/status: Update order status

Testing with Postman
Import the provided Postman collection into your Postman application to test the API endpoints.
