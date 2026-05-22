<?php
/**
 * Database Configuration
 * DUMBLEDORE BOUTIQUE - Luxury Ecommerce Website
 */

// Session Configuration - MUST BE FIRST
session_set_cookie_params([
    'lifetime' => 86400 * 7, // 7 days
    'path' => '/',
    'domain' => '',
    'secure' => false, // Set to true in production with HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Database Connection Details
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dumbledore_boutique');

// Website Settings
define('SITE_URL', 'http://localhost/dumbeldoreboutique/');
define('ADMIN_URL', 'http://localhost/dumbeldoreboutique/admin/');

// Create Database Connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check Connection
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
    
    // Set Character Encoding
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error Reporting - Development Mode
// Change display_errors to 0 for production
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/error.log');
?>
