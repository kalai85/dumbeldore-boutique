<?php
/**
 * Enhanced Add to Cart Handler
 * Improved with better error handling and response
 */

require_once 'config_enhanced.php';
require_once 'helpers.php';

// Verify authentication
if (!is_logged_in()) {
    send_error('Please login to continue', 401);
}

// Verify POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Invalid request method', 400);
}

// Get and validate input
$product_id = intval($_POST['product_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);
$user_id = get_current_user_id();

// Validate inputs
if ($product_id <= 0 || $quantity <= 0 || $quantity > 100) {
    send_error('Invalid product or quantity', 400);
}

try {
    // Check if product exists and has stock
    $stmt = $conn->prepare("SELECT product_id, stock, product_name, price FROM products WHERE product_id = ? AND status = 'active'");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        send_error('Product not found or unavailable', 404);
    }

    $product = $result->fetch_assoc();
    
    // Check stock
    if ($product['stock'] < $quantity) {
        send_error('Only ' . $product['stock'] . ' items available in stock', 400);
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
        
        // Check if new quantity exceeds stock
        if ($newQuantity > $product['stock']) {
            send_error('Cannot add more items. Stock limit reached.', 400);
        }
        
        $stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("ii", $newQuantity, $cart['cart_id']);
        $stmt->execute();
        $action = 'updated';
    } else {
        // Insert new cart item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $action = 'added';
    }
    $stmt->close();

    // Get updated cart count
    $cart_count = get_cart_count($user_id);

    log_message("Product {$product_id} {$action} to cart for user {$user_id}", 'INFO');

    send_success(
        ucfirst($action) . ' to cart successfully',
        [
            'product_name' => $product['product_name'],
            'quantity' => $quantity,
            'cart_count' => $cart_count
        ],
        200
    );

} catch (Exception $e) {
    error_log("Add to cart error: " . $e->getMessage());
    log_message("Add to cart error: " . $e->getMessage(), 'ERROR');
    send_error('An error occurred. Please try again.', 500);
}
?>
