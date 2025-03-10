📦 Inventory Management System
A Laravel-based Inventory Management System designed to help businesses efficiently manage stock, orders, and warehouses.

🚀 Features
Real-time stock tracking
Product categorization
Barcode & QR code integration
Multi-warehouse support
Purchase & sales management
Role-based access control
Automated reports & analytics
Alerts & notifications

🛠️ Installation & Setup
1️⃣ Clone the Repository
git clone repo_link
cd inventory-management-system

2️⃣ Install Dependencies
composer install
npm install

3️⃣ Setup Environment
cp .env.example .env

Then, update your .env file with your database details:
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

4️⃣ Generate App Key
php artisan key:generate

5️⃣ Run Migrations & Seed Database
This will create tables and seed the database with dummy admin credentials.
php artisan db:seed --class=AdminSeeder

✅ Admin Login Details:
Email: admin@gmail.com  
Password: admin@12

6️⃣ Storage & Cache Setup
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

7️⃣ Start the Development Server
php artisan serve

Now, open http://127.0.0.1:8000 in your browser.

🛠️ Admin Panel Login
Visit /admin and log in using the admin credentials above.

📜 License
This project is open-source and available for customization also Will update new versions with Much more changes and adding new features till that use and enjoy for free !.

