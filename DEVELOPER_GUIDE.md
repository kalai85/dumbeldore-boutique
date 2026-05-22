# DEVELOPER QUICK REFERENCE GUIDE

## 🚀 Quick Start Commands

### Start Development (Windows PowerShell):
```powershell
# Open XAMPP Control Panel
Start-Process "C:\xampp\xampp-control.exe"

# Wait for Apache and MySQL to start (green status)
# Then access: http://localhost/dumbeldoreBOUTIQUE/
```

### File Paths Reference:
```
Project Root: C:\xampp\htdocs\dumbeldoreBOUTIQUE\

Key Folders:
- php\         → PHP backend files & config
- admin\       → Admin panel pages
- css\         → Stylesheets
- js\          → JavaScript files
- database\    → MySQL schema
```

---

## 🗄️ Database Quick Reference

### Connection Details:
```php
Host: localhost
User: root
Password: (empty)
Database: dumbledore_boutique
```

### Key Tables & Queries:

#### Users Table:
```sql
-- Get all users
SELECT * FROM users;

-- Get user by email
SELECT * FROM users WHERE email = 'user@example.com';

-- Count total users
SELECT COUNT(*) FROM users;
```

#### Products Table:
```sql
-- Get all products
SELECT * FROM products;

-- Get product with category
SELECT p.*, c.category_name FROM products p 
JOIN categories c ON p.category_id = c.category_id;

-- Get products by category
SELECT * FROM products WHERE category_id = 1;

-- Count active products
SELECT COUNT(*) FROM products WHERE status = 'active';
```

#### Orders Table:
```sql
-- Get all orders
SELECT * FROM orders;

-- Get order with items
SELECT o.*, oi.product_name, oi.quantity FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id;

-- Get user orders
SELECT * FROM orders WHERE user_id = 1;

-- Calculate total revenue (delivered)
SELECT SUM(total_amount) FROM orders WHERE order_status = 'delivered';
```

---

## 👤 Test Credentials

### Admin Account:
```
Email: admin@dumbledore.com
Password: admin@123
```

### Customer Test Account:
- Register a new account on: http://localhost/dumbeldoreBOUTIQUE/register.php
- Or create in database:

```php
<?php
require_once 'php/config.php';

$email = 'test@example.com';
$password = password_hash('Test@123', PASSWORD_BCRYPT);
$fullname = 'Test User';
$phone = '9876543210';

$stmt = $conn->prepare("INSERT INTO users (email, password, fullname, phone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $password, $fullname, $phone);
$stmt->execute();
?>
```

---

## 📝 Common Tasks

### Add New Product Via PHP:
```php
<?php
require_once 'php/config.php';

$product_name = 'New Saree';
$category_id = 1;
$price = 2999.00;
$discount_percent = 10;
$stock = 50;

$stmt = $conn->prepare("INSERT INTO products 
    (product_name, category_id, price, discount_percent, stock, status) 
    VALUES (?, ?, ?, ?, ?, 'active')");
$stmt->bind_param("sidii", $product_name, $category_id, $price, $discount_percent, $stock);
$stmt->execute();
echo "Product added!";
?>
```

### Reset User Password:
```sql
-- Reset to 'password123' (hashed with bcrypt)
UPDATE users SET password = '$2y$10$YourHashedPasswordHere' WHERE user_id = 1;
```

### Clear Shopping Cart:
```sql
-- Clear cart for specific user
DELETE FROM cart WHERE user_id = 1;

-- Clear all carts
DELETE FROM cart;
```

### Check Session Info:
```php
<?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
```

---

## 🔧 Configuration Files

### php/config.php - Database & Session Config:
```php
// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dumbledore_boutique');

// Site URL
define('SITE_URL', 'http://localhost/dumbeldoreBOUTIQUE/');

// Session
session_start();
ini_set('session.gc_maxlifetime', 604800);
ini_set('session.cookie_httponly', 1);
```

### .htaccess - Server Configuration:
- Enable mod_rewrite
- Set browser caching
- Enable gzip compression
- Security headers

---

## 🎨 CSS Custom Properties

Located in `css/style.css`:
```css
:root {
    --primary-gold: #d4af37;
    --light-gold: #f0e68c;
    --dark-bg: #0a0e27;
    --text-light: #ffffff;
    --text-muted: #b0b0b0;
}
```

### Useful CSS Classes:
```html
<!-- Glassmorphism Card -->
<div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px);">

<!-- Gold Button -->
<button class="btn" style="background: #d4af37; color: #0a0e27;">

<!-- Admin Card -->
<div class="card border-0 admin-card">

<!-- Form Input -->
<input type="text" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
```

---

## 🚨 Debugging Tips

### Check PHP Errors:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Your code here
?>
```

### Log Database Queries:
```php
<?php
if ($conn->error) {
    echo "Database Error: " . $conn->error;
    exit;
}
?>
```

### Check Session:
```php
<?php
if (!isset($_SESSION['user_id'])) {
    echo "No active session";
    var_dump($_SESSION);
}
?>
```

### Test AJAX Calls:
```javascript
// In browser console (F12 → Console)
fetch('php/get_cart_count.php')
  .then(r => r.json())
  .then(d => console.log(d))
  .catch(e => console.log('Error:', e));
```

---

## 📦 Project File Structure

```
dumbeldoreBOUTIQUE/
├── Customer Pages (19 files)
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── products.php
│   ├── product-details.php
│   ├── cart.php
│   ├── wishlist.php
│   ├── checkout.php
│   ├── order-confirmation.php
│   ├── order-details.php
│   ├── orders.php
│   ├── profile.php
│   └── search.php
│
├── Admin Pages (10 files)
│   ├── admin/login.php
│   ├── admin/dashboard.php
│   ├── admin/products.php
│   ├── admin/add-product.php
│   ├── admin/edit-product.php
│   ├── admin/categories.php
│   ├── admin/orders.php
│   ├── admin/users.php
│   ├── admin/auth_check.php
│   └── admin/logout.php
│
├── PHP Handlers (7 files)
│   ├── php/config.php
│   ├── php/auth_check.php
│   ├── php/add_to_cart.php
│   ├── php/add_to_wishlist.php
│   ├── php/logout.php
│   ├── php/get_cart_count.php
│   └── php/get_wishlist_count.php
│
├── Assets
│   ├── css/style.css
│   ├── css/admin-style.css
│   ├── js/script.js
│   └── database/database.sql
│
└── Documentation (5 files)
    ├── README.md
    ├── XAMPP_SETUP_GUIDE.md
    ├── INFINITYFREE_DEPLOYMENT.md
    ├── INSTALLATION_CHECKLIST.md
    ├── DEVELOPER_GUIDE.md (this file)
    └── .htaccess
```

---

## 🔐 Security Best Practices

### ✅ Already Implemented:
- SQL injection prevention (prepared statements)
- Password hashing (bcrypt)
- Session validation on all pages
- HTTPONLY session cookies
- CSRF-ready structure

### 🔒 Additional Security:
```php
// Prevent XSS
$safe_input = htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8');

// Validate email
filter_var($email, FILTER_VALIDATE_EMAIL);

// Escape output
echo htmlspecialchars($user_input);

// Prevent direct access
if (basename(__FILE__) == basename($_SERVER['REQUEST_URI'])) {
    exit('Direct access not allowed');
}
```

---

## 🧪 Testing Procedures

### Customer Flow Test:
1. ✅ Register new account
2. ✅ Login with credentials
3. ✅ Browse products
4. ✅ Add to cart
5. ✅ Update quantity
6. ✅ Proceed to checkout
7. ✅ Verify order confirmation
8. ✅ Check order history

### Admin Flow Test:
1. ✅ Login with admin@dumbledore.com / admin@123
2. ✅ View dashboard statistics
3. ✅ Add new product
4. ✅ Edit product
5. ✅ Delete product
6. ✅ View orders
7. ✅ View users

### Browser Tests:
- ✅ Chrome/Edge
- ✅ Firefox
- ✅ Safari
- ✅ Mobile browser

---

## 📱 Responsive Breakpoints

```css
/* Mobile: < 576px */
@media (max-width: 575px) {
    /* Stacked layout */
}

/* Tablet: 576px - 768px */
@media (max-width: 767px) {
    /* Adjusted layout */
}

/* Desktop: 768px+ */
@media (min-width: 768px) {
    /* Full layout */
}
```

---

## 🚀 Performance Optimization

### Images:
- Optimize size before upload
- Use WebP format if possible
- Recommended: < 100KB per image
- Use lazy loading for galleries

### Database:
```sql
-- Optimize tables monthly
OPTIMIZE TABLE users;
OPTIMIZE TABLE products;
OPTIMIZE TABLE orders;

-- Add indexes for better query speed
CREATE INDEX idx_user_id ON cart(user_id);
CREATE INDEX idx_product_id ON products(product_id);
```

### Caching:
- Enable browser caching (.htaccess)
- Minify CSS/JS (optional)
- Use CDN for external libraries

---

## 🆘 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Database connection failed | Check MySQL running, verify config.php credentials |
| Login not working | Verify user exists in database, check password hash |
| Cart not updating | Check session active, test AJAX in console, verify database |
| Page not loading | Check file path, clear cache (Ctrl+Shift+Del), check Apache |
| Images not showing | Verify file path, check server file permissions |
| Session expires quickly | Increase session.gc_maxlifetime in config.php |
| CSS not applied | Clear browser cache, check .htaccess permissions |

---

## 📞 Support & Resources

### Documentation:
- 📖 [README.md](README.md) - Project overview
- 🔧 [XAMPP_SETUP_GUIDE.md](XAMPP_SETUP_GUIDE.md) - Local setup
- 🌐 [INFINITYFREE_DEPLOYMENT.md](INFINITYFREE_DEPLOYMENT.md) - Hosting
- ✅ [INSTALLATION_CHECKLIST.md](INSTALLATION_CHECKLIST.md) - Verification

### External Resources:
- PHP Docs: https://www.php.net/docs.php
- Bootstrap: https://getbootstrap.com/docs/5.3/
- MySQL: https://dev.mysql.com/doc/
- MDN: https://developer.mozilla.org/

---

**Last Updated:** May 2026
**Project:** DUMBLEDORE BOUTIQUE v1.0
**Version:** Developer Guide v1.0
