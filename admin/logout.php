<?php
/**
 * Admin Logout Handler
 * Properly destroy admin session and redirect to admin login
 */

require_once dirname(__DIR__) . '/php/config.php';

// Destroy all session data
session_destroy();

// Redirect to admin login page
header('Location: ' . ADMIN_URL . 'login.php');
exit();
?>
