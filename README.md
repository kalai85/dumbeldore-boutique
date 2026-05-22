# DUMBLEDORE BOUTIQUE - Premium Luxury Ecommerce Website

## 🎭 Project Overview

DUMBLEDORE BOUTIQUE is an advanced full-stack ecommerce website built with HTML, CSS, Bootstrap 5, JavaScript, PHP, and MySQL. It features a premium black and gold luxury design with glassmorphism effects, magical animations, and comprehensive functionality for both customers and administrators.

## 🌟 Key Features

### Customer Features
- ✅ **Premium Authentication System** - Secure login/register with email verification
- ✅ **Session Protection** - All pages require authentication
- ✅ **Product Catalog** - Browse products with filters, search, and sorting
- ✅ **Shopping Cart** - Add/remove items with live calculation
- ✅ **Wishlist System** - Save favorite products
- ✅ **Checkout Process** - Complete order placement
- ✅ **User Dashboard** - View profile, orders, wishlist
- ✅ **Product Details** - Detailed view with reviews and ratings
- ✅ **Order History** - Track all orders
- ✅ **Payment Options** - COD, Credit/Debit Card, UPI, Net Banking

### Admin Features
- ✅ **Admin Dashboard** - Sales statistics and overview
- ✅ **Product Management** - Add, edit, delete products
- ✅ **Order Management** - View and manage all orders
- ✅ **User Management** - Monitor registered users
- ✅ **Category Management** - Manage product categories
- ✅ **Stock Management** - Track inventory

### Design Features
- ✅ **Black and Gold Luxury Design** - Premium aesthetic
- ✅ **Glassmorphism Effects** - Modern blur effects
- ✅ **Magical Animations** - AOS animations and transitions
- ✅ **Responsive Design** - Mobile-friendly layout
- ✅ **Smooth Transitions** - Elegant hover effects
- ✅ **Particle Effects** - Animated background particles

## 📁 Project Structure

```
dumbeldoreBOUTIQUE/
├── index.php                 # Redirect to login
├── login.php                 # Customer login page
├── register.php              # Customer registration page
├── dashboard.php             # Homepage/dashboard
├── products.php              # Products listing
├── product-details.php       # Product details page
├── cart.php                  # Shopping cart
├── wishlist.php              # Wishlist page
├── checkout.php              # Checkout process
├── order-confirmation.php    # Order confirmation
├── orders.php                # User order history
├── profile.php               # User profile
├── search.php                # Search results
│
├── admin/
│   ├── login.php             # Admin login
│   ├── dashboard.php         # Admin dashboard
│   ├── products.php          # Manage products
│   ├── add-product.php       # Add new product
│   ├── edit-product.php      # Edit product
│   ├── orders.php            # Manage orders
│   ├── users.php             # Manage users
│   ├── categories.php        # Manage categories
│   ├── auth_check.php        # Admin auth check
│   └── logout.php            # Admin logout
│
├── php/
│   ├── config.php            # Database configuration
│   ├── auth_check.php        # Session verification
│   ├── add_to_cart.php       # Add to cart handler
│   ├── add_to_wishlist.php   # Wishlist handler
│   ├── logout.php            # Logout handler
│   ├── get_cart_count.php    # Cart count AJAX
│   └── get_wishlist_count.php # Wishlist count AJAX
│
├── css/
│   ├── style.css             # Main stylesheet
│   └── admin-style.css       # Admin panel styles
│
├── js/
│   └── script.js             # Main JavaScript file
│
├── database/
│   └── database.sql          # Complete database schema
│
├── images/
│   └── (Product images folder)
│
├── uploads/
│   └── (User uploads folder)
│
└── README.md                 # This file
```

## 🚀 Setup Instructions

### Prerequisites
- XAMPP or similar PHP server
- MySQL/MariaDB
- Web browser

### Step 1: Download the Project
1. Copy the `dumbeldoreBOUTIQUE` folder to `C:\xampp\htdocs\`

### Step 2: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `dumbledore_boutique`
3. Import the `database/database.sql` file:
   - Click on the new database
   - Go to "Import" tab
   - Select `database.sql` file
   - Click "Import"

### Step 3: Configure Database Connection
1. The `php/config.php` file is already configured for XAMPP default settings
2. If using different credentials, update:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'dumbledore_boutique');
   ```

### Step 4: Start XAMPP
1. Open XAMPP Control Panel
2. Start Apache and MySQL modules

### Step 5: Access the Website
- **Customer Site**: http://localhost/dumbeldoreBOUTIQUE/
- **Admin Panel**: http://localhost/dumbeldoreBOUTIQUE/admin/login.php

### Step 6: Login Credentials

**Customer Test Account:**
- Email: (Register new account or use database seeded users)
- Password: (Use the password you set)

**Admin Account:**
- Email: `admin@dumbledore.com`
- Password: `admin@123`

## 🗄️ Database Schema

### Tables Created:
1. **users** - Customer accounts
2. **admin** - Admin accounts
3. **categories** - Product categories
4. **products** - Product listings
5. **product_images** - Product gallery images
6. **cart** - Shopping cart items
7. **wishlist** - Wishlist items
8. **orders** - Order information
9. **order_items** - Individual order items
10. **reviews** - Product reviews and ratings
11. **coupons** - Discount coupons

## 🎨 Color Scheme

- **Primary Gold**: #d4af37
- **Light Gold**: #f0e68c
- **Dark Background**: #0a0e27
- **Text Light**: #ffffff
- **Text Muted**: #b0b0b0

## 📝 Features Breakdown

### Authentication
- Secure password hashing using BCrypt
- Session-based authentication
- Session timeout after 7 days
- Automatic redirect to login for unauthorized access

### Product Management
- Browse products by category
- Search functionality
- Filter by price, color, material
- Sort by latest, price, rating
- Product ratings and reviews
- Stock management

### Cart & Checkout
- Add/remove items
- Quantity management
- Real-time total calculation
- Multiple payment methods
- Order tracking

### Admin Dashboard
- Real-time statistics
- Product CRUD operations
- Order management
- User management
- Sales analytics

## 🔒 Security Features

- SQL injection prevention (Prepared statements)
- Password hashing (BCrypt)
- Session validation
- CSRF protection ready
- Input validation
- Error handling

## 📱 Responsive Design

- Mobile-first approach
- Bootstrap 5 grid system
- Flexbox layouts
- Touch-friendly buttons
- Optimized navigation

## 🎯 Future Enhancements

- Email notifications
- SMS integration
- Advanced analytics
- Rating/review system
- Discount coupon system
- Shipping tracking
- Customer support chat
- Social media integration
- Payment gateway integration (Razorpay, Stripe)

## 📧 Support

For issues or questions:
- Email: info@dumbledore.com
- Phone: +91 98765 43210
- WhatsApp: Available on website

## 📄 License

DUMBLEDORE BOUTIQUE © 2024. All rights reserved.

---

**Build with ❤️ and Magic ✨**
