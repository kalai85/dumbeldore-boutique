<?php
/**
 * Session Protection
 * Check if user is logged in, if not redirect to login
 */

require_once __DIR__ . '/config.php';

// Verify user session exists
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    session_destroy();
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

// Validate session timeout (7 days)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 604800)) {
    session_destroy();
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

// Update session activity time
$_SESSION['login_time'] = time();
?>
