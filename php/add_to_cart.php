<?php
/**
 * Add to Cart Handler - AJAX
 * Secure JSON API endpoint with proper error handling
 */

require_once 'config.php';
require_once 'auth_check.php';

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Verify POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get and validate input
$product_id = intval($_POST['product_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);
$user_id = $_SESSION['user_id'];

// Validate inputs
if ($product_id <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
    exit();
}

try {
    // Check if product exists and has stock
    $stmt = $conn->prepare("SELECT product_id, stock FROM products WHERE product_id = ? AND status = 'active'");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    $product = $result->fetch_assoc();
    if ($product['stock'] < $quantity) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Insufficient stock available']);
        exit();
    }
    $stmt->close();

    // Check if product already in cart
    $stmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $cartResult = $stmt->get_result();

    if ($cartResult->num_rows > 0) {
        // Update existing cart item
        $cart = $cartResult->fetch_assoc();
        $newQuantity = $cart['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("ii", $newQuantity, $cart['cart_id']);
        $stmt->execute();
    } else {
        // Insert new cart item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }
    $stmt->close();

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Added to cart successfully']);

} catch (Exception $e) {
    http_response_code(500);
    error_log("Add to cart error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
}
?>
