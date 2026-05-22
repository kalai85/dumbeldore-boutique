<?php
/**
 * Enhanced Database Configuration & Setup
 * DUMBLEDORE BOUTIQUE - Luxury Ecommerce Website
 * 
 * Modern PHP 7.4+ configuration with improved security,
 * error handling, and utilities
 */

// ============================================
// SESSION CONFIGURATION - MUST BE FIRST
// ============================================
session_set_cookie_params([
    'lifetime' => 86400 * 7,    // 7 days
    'path' => '/',
    'domain' => '',
    'secure' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Enable strict session mode
ini_set('session.use_strict_mode', 1);
ini_set('session.sid_length', 64);
ini_set('session.sid_bits_per_character', 6);

session_start();

// ============================================
// ENVIRONMENT CONFIGURATION
// ============================================

// Get environment (development/production)
$ENV = getenv('APP_ENV') ?: 'development';
define('APP_ENV', $ENV);
define('IS_PRODUCTION', APP_ENV === 'production');

// Database Connection Details
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'dumbledore_boutique');

// Website Settings
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/dumbeldoreboutique/');
define('ADMIN_URL', SITE_URL . 'admin/');
define('UPLOADS_DIR', __DIR__ . '/../uploads/');
define('UPLOADS_URL', SITE_URL . 'uploads/');

// ============================================
// ERROR REPORTING CONFIGURATION
// ============================================

// Development mode - show errors (change for production)
if (IS_PRODUCTION) {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// ============================================
// TIMEZONE CONFIGURATION
// ============================================
date_default_timezone_set('Asia/Kolkata');

// ============================================
// DATABASE CONNECTION
// ============================================

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection Failed: " . $conn->connect_error);
    }
    
    // Set character encoding to UTF-8
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }
    
    // Set SQL mode for better compatibility
    $conn->query("SET sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    
    if (IS_PRODUCTION) {
        die("A database error occurred. Please try again later.");
    } else {
        die("Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}

// ============================================
// GLOBAL SECURITY HEADERS
// ============================================

// Prevent X-Frame-Clickjacking
header('X-Frame-Options: SAMEORIGIN');

// Prevent MIME type sniffing
header('X-Content-Type-Options: nosniff');

// Enable XSS protection
header('X-XSS-Protection: 1; mode=block');

// Content Security Policy (adjust as needed)
header("Content-Security-Policy: default-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' https://cdnjs.cloudflare.com;");

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Safe output - prevents XSS attacks
 */
function h($string, $flags = ENT_QUOTES, $encoding = 'UTF-8') {
    return htmlspecialchars($string, $flags, $encoding);
}

/**
 * JSON Response Helper
 */
function json_response($success, $message = '', $data = null, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    
    $response = [
        'success' => (bool)$success,
        'message' => (string)$message,
        'timestamp' => date('c')
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit();
}

/**
 * Redirect to URL
 */
function redirect($url, $code = 302) {
    header("Location: " . $url, true, $code);
    exit();
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID (safe)
 */
function get_current_user_id() {
    return isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
}

/**
 * Generate CSRF Token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Log message to file
 */
function log_message($message, $type = 'INFO') {
    $log_dir = __DIR__ . '/../logs/';
    
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_file = $log_dir . date('Y-m-d') . '.log';
    $log_entry = '[' . date('Y-m-d H:i:s') . '] [' . $type . '] ' . $message . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Format price in INR
 */
function format_price($price) {
    return '₹' . number_format((float)$price, 2, '.', ',');
}

/**
 * Safe database error handler
 */
function db_error($message = 'Database operation failed') {
    error_log("DB Error: " . $message);
    return IS_PRODUCTION ? "An error occurred" : $message;
}

// ============================================
// ERROR & EXCEPTION HANDLERS
// ============================================

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    
    $error_message = "[$errno] $errstr in $errfile:$errline";
    log_message($error_message, 'ERROR');
    
    if (!IS_PRODUCTION && php_sapi_name() !== 'cli') {
        echo '<div style="background:#f8d7da;color:#721c24;padding:15px;margin:10px;border:1px solid #f5c6cb;border-radius:4px;">';
        echo '<strong>Error:</strong> ' . h($error_message);
        echo '</div>';
    }
});

set_exception_handler(function($exception) {
    $error_message = $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine();
    log_message($error_message, 'EXCEPTION');
    
    if (!IS_PRODUCTION) {
        die("Exception: " . h($error_message));
    } else {
        die("An unexpected error occurred. Please try again later.");
    }
});

// ============================================
// DEPRECATION: Legacy function for backward compatibility
// ============================================

/**
 * @deprecated Use is_logged_in() instead
 */
function check_auth() {
    if (!is_logged_in()) {
        redirect(SITE_URL . 'login.php');
    }
}

?>
