# DUMBLEDORE BOUTIQUE - XAMPP Local Setup Guide

## 🎯 Quick Start Guide for Local Development

### Prerequisites
- Windows 10/11 or macOS or Linux
- Minimum 2GB RAM
- 500MB free disk space
- Administrator access

---

## Step 1: Download and Install XAMPP

### For Windows:
1. Go to https://www.apachefriends.org/download.html
2. Download **XAMPP for Windows** (Latest version)
3. Run the installer (e.g., `xampp-windows-x64-8.2.0-installer.exe`)
4. Choose installation path: `C:\xampp`
5. Select components:
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin
6. Complete the installation

### For macOS:
```bash
# Install using Homebrew (recommended)
brew install xampp

# Or download from https://www.apachefriends.org/download.html
```

### For Linux (Ubuntu/Debian):
```bash
# Download the installer
wget https://www.apachefriends.org/xampp-files/8.2.0/xampp-linux-x64-8.2.0-installer.run

# Make executable
chmod +x xampp-linux-x64-8.2.0-installer.run

# Run installer
sudo ./xampp-linux-x64-8.2.0-installer.run
```

---

## Step 2: Start XAMPP Services

### Windows:
1. Open XAMPP Control Panel (`C:\xampp\xampp-control.exe`)
2. Click **Start** next to:
   - Apache
   - MySQL
3. Status should show **green** indicators

### macOS/Linux:
```bash
# Start XAMPP
sudo /Applications/XAMPP/xamppfiles/bin/xampp start

# Or for Linux
sudo /opt/lampp/xampp start
```

✅ **Verify**: Visit http://localhost in browser - should show XAMPP welcome page

---

## Step 3: Project Setup

### 1. Copy Project Files

**Windows:**
```
C:\xampp\htdocs\dumbeldoreBOUTIQUE\
```

**macOS:**
```
/Applications/XAMPP/xamppfiles/htdocs/dumbeldoreBOUTIQUE/
```

**Linux:**
```
/opt/lampp/htdocs/dumbeldoreBOUTIQUE/
```

### 2. Create MySQL Database

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Login (Default: Username: `root`, Password: `empty`)
3. Click **New** (or use SQL tab)
4. Create database named: `dumbledore_boutique`

### 3. Import Database Schema

1. In phpMyAdmin, select `dumbledore_boutique` database
2. Go to **Import** tab
3. Click **Choose File** and select `database/database.sql`
4. Click **Import**

✅ **Verify**: Should show tables created (users, products, orders, etc.)

### 4. Verify Configuration

Edit `php/config.php` and ensure settings match XAMPP defaults:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dumbledore_boutique');
define('SITE_URL', 'http://localhost/dumbeldoreBOUTIQUE/');
?>
```

---

## Step 4: Access the Website

### Customer Site:
```
http://localhost/dumbeldoreBOUTIQUE/
```

This automatically redirects to:
```
http://localhost/dumbeldoreBOUTIQUE/login.php
```

### Admin Panel:
```
http://localhost/dumbeldoreBOUTIQUE/admin/login.php
```

---

## Step 5: Test Login Credentials

### Customer Account:
1. **Register a new account:**
   - Go to Register page
   - Fill in: Email, Full Name, Phone, Password
   - Click Register
   - Redirects to Login

2. **Login:**
   - Email: (Use the registered email)
   - Password: (Use the registered password)

### Admin Account:
- **Email:** `admin@dumbledore.com`
- **Password:** `admin@123`

---

## Troubleshooting

### Issue 1: "Connection refused" Error
**Solution:**
- Make sure Apache and MySQL are running (green in XAMPP Control Panel)
- Restart both services
- Check firewall settings

### Issue 2: "Database connection failed"
**Solution:**
1. Verify MySQL is running
2. Check `php/config.php` credentials
3. Ensure database `dumbledore_boutique` exists:
   - Visit phpMyAdmin and create if missing
   - Import `database/database.sql`

### Issue 3: Login page doesn't load
**Solution:**
- Clear browser cache (Ctrl+Shift+Delete)
- Check file permissions on `php/` folder
- Verify `php/config.php` exists and is readable

### Issue 4: Session expires too quickly
**Solution:**
1. Edit `php/config.php`
2. Adjust session lifetime:
   ```php
   ini_set('session.gc_maxlifetime', 604800); // 7 days
   ```
3. Restart browser

### Issue 5: WhatsApp button not working
**Solution:**
- Update phone number in relevant page PHP files
- Ensure internet connection is active
- Test: `https://wa.me/919876543210?text=Hello`

---

## File Structure Reference

```
C:\xampp\htdocs\dumbeldoreBOUTIQUE\
├── index.php                 # Redirect to login
├── login.php                 # Customer login
├── register.php              # Customer registration
├── dashboard.php             # Homepage
├── products.php              # Product catalog
├── product-details.php       # Product details
├── cart.php                  # Shopping cart
├── wishlist.php              # Wishlist
├── checkout.php              # Checkout
├── order-confirmation.php    # Order success
├── order-details.php         # Order details
├── orders.php                # Order history
├── profile.php               # User profile
├── search.php                # Search results
│
├── admin/
│   ├── login.php             # Admin login
│   ├── dashboard.php         # Admin dashboard
│   ├── products.php          # Manage products
│   ├── add-product.php       # Add product
│   ├── edit-product.php      # Edit product
│   ├── categories.php        # Manage categories
│   ├── orders.php            # Manage orders
│   ├── users.php             # Manage users
│   ├── auth_check.php        # Admin session check
│   └── logout.php            # Admin logout
│
├── php/
│   ├── config.php            # Database config
│   ├── auth_check.php        # Session check
│   ├── add_to_cart.php       # Cart AJAX
│   ├── add_to_wishlist.php   # Wishlist AJAX
│   ├── get_cart_count.php    # Cart count
│   ├── get_wishlist_count.php# Wishlist count
│   └── logout.php            # Logout handler
│
├── css/
│   ├── style.css             # Main styling
│   └── admin-style.css       # Admin styling
│
├── js/
│   └── script.js             # Main JavaScript
│
├── database/
│   └── database.sql          # Database schema
│
└── README.md                 # Project documentation
```

---

## Port Configuration

### Default XAMPP Ports:
- **HTTP:** `80` (http://localhost)
- **HTTPS:** `443` (https://localhost)
- **MySQL:** `3306`
- **phpMyAdmin:** `http://localhost/phpmyadmin`

### Change Apache Port (if 80 is used):
1. Open `C:\xampp\apache\conf\httpd.conf`
2. Find: `Listen 80`
3. Change to: `Listen 8080`
4. Restart Apache
5. Access: `http://localhost:8080/dumbeldoreBOUTIQUE/`

### Change MySQL Port (if 3306 is used):
1. Open `C:\xampp\mysql\bin\my.ini`
2. Find: `port=3306`
3. Change to: `port=3307`
4. Update `php/config.php`:
   ```php
   define('DB_HOST', 'localhost:3307');
   ```
5. Restart MySQL

---

## Development Tips

### Enable Error Logging:
1. Open `php.ini` in `C:\xampp\php\`
2. Find: `error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT`
3. Change to: `error_reporting = E_ALL`
4. Save and restart Apache

### Monitor PHP Errors:
- Check: `C:\xampp\apache\logs\error.log`
- Check: `C:\xampp\php\logs\` (if exists)

### Database Backup:
1. Open phpMyAdmin
2. Select `dumbledore_boutique`
3. Click **Export**
4. Choose **SQL** format
5. Click **Go** to download backup

### Database Restore:
1. Open phpMyAdmin
2. Create new database or select existing
3. Go to **Import**
4. Select SQL backup file
5. Click **Import**

---

## Performance Optimization

### Increase PHP Memory Limit:
Edit `C:\xampp\php\php.ini`:
```
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 50M
post_max_size = 50M
```

### Enable Caching:
Configure `.htaccess` in project root:
```apache
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpeg "access plus 1 month"
  ExpiresByType image/gif "access plus 1 month"
  ExpiresByType image/png "access plus 1 month"
  ExpiresByType text/css "access plus 1 week"
  ExpiresByType text/javascript "access plus 1 week"
</IfModule>
```

---

## Next Steps

1. ✅ **Local Development Complete** - Test all features locally
2. 📱 **Mobile Testing** - Use device's IP: `http://YOUR_IP/dumbeldoreBOUTIQUE/`
3. 🌐 **Deploy to InfinityFree** - See [InfinityFree Deployment Guide](INFINITYFREE_DEPLOYMENT.md)
4. 🚀 **Production Setup** - Use proper hosting with SSL

---

## Support & Troubleshooting

- **XAMPP Official:** https://www.apachefriends.org/
- **phpMyAdmin Docs:** https://www.phpmyadmin.net/
- **PHP Documentation:** https://www.php.net/docs.php
- **Bootstrap Docs:** https://getbootstrap.com/docs/5.3/

---

**Last Updated:** May 2026
**Version:** 1.0
**Project:** DUMBLEDORE BOUTIQUE
