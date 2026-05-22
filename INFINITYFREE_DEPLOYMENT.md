# DUMBLEDORE BOUTIQUE - InfinityFree Deployment Guide

## 🌐 Deploy to InfinityFree Hosting

### Overview
This guide helps you deploy DUMBLEDORE BOUTIQUE from XAMPP localhost to **InfinityFree** free hosting platform.

---

## Prerequisites

1. **InfinityFree Account** - Free sign up at https://www.infinityfree.net/
2. **FTP Client** - Download FileZilla (https://filezilla-project.org/)
3. **Project Files** - Complete DUMBLEDORE BOUTIQUE folder
4. **Domain** (Optional) - Your own domain or use InfinityFree subdomain

---

## Step 1: Create InfinityFree Account

1. Visit https://www.infinityfree.net/
2. Click **Sign Up**
3. Fill in:
   - Email
   - Username
   - Password
4. Verify email
5. Login to Dashboard

---

## Step 2: Create Hosting Account

1. Go to **Dashboard**
2. Click **Create New Account**
3. Choose your subdomain:
   - `yourname.infinityfreeapp.com`
   - Or use custom domain (requires additional setup)
4. Accept Terms & Create Account

⏱️ **Wait 5-10 minutes** for account activation

---

## Step 3: Configure FTP Access

### In InfinityFree Dashboard:

1. Go to **Manage Account**
2. Look for **FTP Details**
3. Note down:
   - **FTP Hostname:** Usually `ftpXX.infinity free.net`
   - **FTP Username:** Your account username
   - **FTP Password:** Account password
   - **Directory:** `/htdocs/` (public_html equivalent)

### Create FTP Account (if needed):

1. **Manage Account** → **FTP Accounts**
2. Click **Create New FTP Account**
3. Set name: `dumbledore`
4. Set password
5. Save credentials

---

## Step 4: Upload Project Files via FTP

### Using FileZilla:

1. **Install FileZilla** from https://filezilla-project.org/
2. Open FileZilla
3. **File** → **Site Manager**
4. Click **New Site**
5. Fill in:
   - **Protocol:** FTP
   - **Host:** `ftpXX.infinityfree.net` (from InfinityFree)
   - **Encryption:** Only use plain FTP
   - **Logon Type:** Normal
   - **User:** Your FTP username
   - **Password:** Your FTP password
6. Click **Connect**

### Upload Files:

1. **Left Panel** (Local): Navigate to `C:\xampp\htdocs\dumbeldoreBOUTIQUE\`
2. **Right Panel** (Remote): Navigate to `/htdocs/`
3. Create folder: `dumbeldoreBOUTIQUE`
4. Select all project files and folders
5. **Drag & Drop** or right-click **Upload** to `/htdocs/dumbeldoreBOUTIQUE/`

⏱️ **Wait for upload** to complete (5-10 minutes depending on speed)

---

## Step 5: Create and Import Database

### In InfinityFree Dashboard:

1. Go to **Manage Account** → **MySQL Databases**
2. Create New Database:
   - **Database Name:** `dumbledore_boutique` (auto-prefixed)
   - **Username:** Create new or use existing
   - **Password:** Set strong password
3. Click **Create Database**

### Import Database Schema:

1. Go to **phpMyAdmin** link in Dashboard
2. Login with database credentials
3. Select your database
4. Click **Import** tab
5. Choose file: `database/database.sql` from project
6. Click **Import**

✅ **Verify:** Tables should appear (users, products, orders, etc.)

---

## Step 6: Update Configuration File

### Edit `php/config.php`:

1. Download `php/config.php` from server via FTP
2. Edit in text editor
3. Update database credentials:

```php
<?php
// InfinityFree Database Config
define('DB_HOST', 'localhost'); // Usually localhost for InfinityFree
define('DB_USER', 'your_username_here');     // From MySQL creation
define('DB_PASS', 'your_password_here');     // Database password
define('DB_NAME', 'yournamedb_boutique');    // Auto-prefixed name from InfinityFree

// Site URL - Update to your domain
define('SITE_URL', 'https://yourname.infinityfreeapp.com/dumbeldoreBOUTIQUE/');

// Or if using custom domain
define('SITE_URL', 'https://yourdomain.com/dumbeldoreBOUTIQUE/');

// Rest of config remains same
session_start();
ini_set('session.gc_maxlifetime', 604800);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
date_default_timezone_set('Asia/Kolkata');
?>
```

4. Upload back to server via FTP

---

## Step 7: Set Correct File Permissions

### Important for Security:

1. Via FTP, right-click each folder:
   - `/dumbeldoreBOUTIQUE/uploads/` → **Permissions:** `755`
   - `/dumbeldoreBOUTIQUE/php/` → **Permissions:** `755`
   - `/dumbeldoreBOUTIQUE/database/` → **Permissions:** `755`

2. For files, use `644` permissions

**FileZilla:**
- Right-click folder → **File Attributes** → Set permissions

---

## Step 8: Access Your Live Website

### Customer Site:
```
https://yourname.infinityfreeapp.com/dumbeldoreBOUTIQUE/
```

Redirects to:
```
https://yourname.infinityfreeapp.com/dumbeldoreBOUTIQUE/login.php
```

### Admin Panel:
```
https://yourname.infinityfreeapp.com/dumbeldoreBOUTIQUE/admin/login.php
```

---

## Step 9: Test Login

### Register Customer Account:
1. Go to Register page
2. Fill in details
3. Click Register
4. Login with your credentials
5. Test all features: Browse, Add to Cart, Checkout

### Login to Admin:
- **Email:** `admin@dumbledore.com`
- **Password:** `admin@123`
- Verify admin dashboard loads

---

## Step 10: Configure Custom Domain (Optional)

### If using custom domain (e.g., `dumbledoréboutique.com`):

1. **In InfinityFree:**
   - **Manage Account** → **Addon Domains**
   - Add your domain
   - Point nameservers to InfinityFree

2. **Update `php/config.php`:**
   ```php
   define('SITE_URL', 'https://yourdomain.com/dumbeldoreBOUTIQUE/');
   ```

3. **Enable SSL Certificate:**
   - InfinityFree provides free SSL
   - Should be auto-enabled
   - Verify: Green lock icon in browser

---

## Troubleshooting

### Issue 1: "Connection Refused" Error
**Solution:**
1. Verify database credentials in `php/config.php`
2. Check if database was created in InfinityFree
3. Verify FTP uploaded all files correctly

### Issue 2: Blank Page / 500 Error
**Solution:**
1. Enable error reporting in `php/config.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
2. Check FTP for correct folder path
3. Verify file permissions (755 for folders, 644 for files)

### Issue 3: Login Not Working
**Solution:**
1. Verify database tables exist in phpMyAdmin
2. Check if admin user was imported (email: admin@dumbledore.com)
3. Verify password hashing (should use bcrypt)
4. Clear browser cookies and try again

### Issue 4: Session Expires Immediately
**Solution:**
1. Update `php/config.php`:
   ```php
   ini_set('session.gc_maxlifetime', 604800);
   session_set_cookie_params(604800);
   ```
2. Check server time zone is correct
3. Restart and test

### Issue 5: Images/CSS Not Loading
**Solution:**
1. Verify URL in `php/config.php` SITE_URL is correct
2. Check file paths in CSS/JS files
3. Reload page (Ctrl+Shift+R) to clear cache

### Issue 6: WhatsApp Button Not Working
**Solution:**
1. Update phone number in PHP files
2. Use international format: `+91XXXXXXXXXX`
3. Test link: `https://wa.me/91XXXXXXXXXX`

### Issue 7: FTP Connection Failed
**Solution:**
1. Verify FTP credentials from InfinityFree Dashboard
2. Use correct hostname (usually `ftpXX.infinityfree.net`)
3. Ensure account is fully activated (wait 10+ minutes)
4. Try different FTP client if FileZilla fails

---

## Database Backup on InfinityFree

### Backup Steps:
1. Login to phpMyAdmin
2. Select your database
3. Click **Export**
4. Format: SQL
5. Click **Go** to download
6. **Save locally** for safety

### Restore Backup:
1. In phpMyAdmin
2. Go to **Import**
3. Select backup SQL file
4. Click **Import**

---

## Performance Tips

### Optimize Images:
- Compress images before uploading
- Use appropriate formats (WebP, PNG, JPEG)
- Recommended size: < 100KB per image

### Database Optimization:
```sql
-- In phpMyAdmin SQL tab, optimize tables:
OPTIMIZE TABLE users;
OPTIMIZE TABLE products;
OPTIMIZE TABLE orders;
OPTIMIZE TABLE cart;
OPTIMIZE TABLE wishlist;
```

### Caching:
- Enable browser caching in `.htaccess`
- Minify CSS and JavaScript
- Use CDN for external libraries (Bootstrap, FontAwesome)

---

## Security Checklist

- ✅ Change admin password from default
- ✅ Update SITE_URL correctly
- ✅ Use HTTPS (free SSL from InfinityFree)
- ✅ Set file permissions correctly (755, 644)
- ✅ Remove database.sql from public folder (optional)
- ✅ Regular database backups
- ✅ Keep PHP updated
- ✅ Use strong user passwords

---

## Monthly Maintenance

1. **Backup Database** - Weekly
2. **Check Error Logs** - Weekly
3. **Test All Features** - Monthly
4. **Update Passwords** - Every 3 months
5. **Review User Accounts** - Monthly
6. **Check Disk Usage** - Monthly

---

## Limitations of Free Hosting

⚠️ **Be Aware:**
- Limited disk space (~5GB)
- Limited bandwidth
- No advanced cron jobs
- Limited email accounts
- No advanced SSL options
- Ads may be displayed (with free plan)

### Upgrade Options:
- Premium plans available on InfinityFree
- Or migrate to better hosting (AWS, DigitalOcean, Bluehost)

---

## Migration to Better Hosting

When ready to upgrade:

1. **Export Database:**
   - phpMyAdmin → Export → SQL format

2. **Download All Files:**
   - FTP → Select all → Download locally

3. **Update config.php** for new host credentials

4. **Upload to new hosting**

5. **Import database** on new host

6. **Update DNS/Domain** if needed

---

## Support Resources

- **InfinityFree Help:** https://help.infinityfree.net/
- **phpMyAdmin Support:** https://www.phpmyadmin.net/
- **FTP Troubleshooting:** https://filezilla-project.org/wiki/
- **PHP on Shared Hosting:** https://www.php.net/manual/

---

## Quick Reference

| Item | Value |
|------|-------|
| FTP Hostname | ftpXX.infinityfree.net |
| FTP Port | 21 |
| MySQL Host | localhost |
| Admin Email | admin@dumbledore.com |
| Admin Password | admin@123 |
| Site URL | https://yourname.infinityfreeapp.com/dumbeldoreBOUTIQUE/ |
| Database Name | yournamedb_boutique (auto-prefixed) |

---

**Last Updated:** May 2026
**Version:** 1.0
**Project:** DUMBLEDORE BOUTIQUE

---

✅ **Deployment Complete!** Your DUMBLEDORE BOUTIQUE is now live on InfinityFree.

📧 For support: info@dumbledore.com
🌐 Visit: https://yourname.infinityfreeapp.com/dumbeldoreBOUTIQUE/
