<?php
/**
 * Logout Handler
 * Properly destroy session and redirect to login
 */

require_once __DIR__ . '/config.php';

// Destroy all session data
session_destroy();

// Redirect to login page
header('Location: ' . SITE_URL . 'login.php');
exit();
?>
