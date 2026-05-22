<?php
/**
 * Admin Auth Check
 * Verify admin is logged in
 */

require_once dirname(__DIR__) . '/php/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ' . dirname(__DIR__) . '/admin/login.php');
    exit();
}
?>
