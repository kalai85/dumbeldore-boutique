<?php
/**
 * Get Wishlist Count - AJAX Handler
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode(['count' => $result['total'] ?? 0]);
?>
