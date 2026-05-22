<?php
/**
 * Get Cart Count - AJAX Handler
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

$stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode(['count' => $result['total'] ?? 0]);
?>
