<?php
/**
 * Helper Functions & Utilities
 * DUMBLEDORE BOUTIQUE
 */

// ============================================
// CART HELPERS
// ============================================

/**
 * Get cart count for user
 */
function get_cart_count($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    if (!$stmt) {
        return 0;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return $result['total'] ?? 0;
}

/**
 * Get wishlist count for user
 */
function get_wishlist_count($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?");
    if (!$stmt) {
        return 0;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return $result['total'] ?? 0;
}

/**
 * Get cart items with product details
 */
function get_cart_items($user_id) {
    global $conn;
    
    $query = "SELECT c.*, p.product_name, p.price, p.discount_percent, p.product_image 
              FROM cart c 
              JOIN products p ON c.product_id = p.product_id 
              WHERE c.user_id = ?
              ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($item = $result->fetch_assoc()) {
        $item['final_price'] = $item['price'] - ($item['price'] * $item['discount_percent'] / 100);
        $item['item_total'] = $item['final_price'] * $item['quantity'];
        $items[] = $item;
    }
    
    $stmt->close();
    return $items;
}

/**
 * Calculate cart total
 */
function calculate_cart_total($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['item_total'] ?? 0;
    }
    return $total;
}

// ============================================
// PRODUCT HELPERS
// ============================================

/**
 * Get product by ID with full details
 */
function get_product($product_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND status = 'active'");
    if (!$stmt) {
        return null;
    }
    
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return $result;
}

/**
 * Get final price after discount
 */
function get_final_price($price, $discount_percent) {
    $discount_amount = ($price * $discount_percent) / 100;
    return $price - $discount_amount;
}

/**
 * Get products with pagination
 */
function get_products($category_id = null, $search = '', $sort = 'latest', $page = 1, $per_page = 12) {
    global $conn;
    
    $where = "WHERE p.status = 'active'";
    $params = [];
    $param_types = "";
    
    if ($category_id) {
        $where .= " AND p.category_id = ?";
        $params[] = $category_id;
        $param_types .= "i";
    }
    
    if (!empty($search)) {
        $where .= " AND (p.product_name LIKE ? OR p.color LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $param_types .= "ss";
    }
    
    // Sort logic
    $order = "p.created_at DESC";
    if ($sort === 'price_low') {
        $order = "p.price ASC";
    } elseif ($sort === 'price_high') {
        $order = "p.price DESC";
    } elseif ($sort === 'rating') {
        $order = "p.rating DESC";
    }
    
    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM products p {$where}";
    $count_stmt = $conn->prepare($count_query);
    
    if ($param_types) {
        $count_stmt->bind_param($param_types, ...$params);
    }
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
    $count_stmt->close();
    
    // Calculate pagination
    $total_pages = ceil($total / $per_page);
    $offset = ($page - 1) * $per_page;
    
    // Get products
    $query = "SELECT p.* FROM products p {$where} ORDER BY {$order} LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $param_types .= "ii";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
    $stmt->close();
    
    return [
        'products' => $products,
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => $total_pages
    ];
}

// ============================================
// USER HELPERS
// ============================================

/**
 * Get user data safely
 */
function get_user($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT user_id, fullname, email, phone, created_at FROM users WHERE user_id = ?");
    if (!$stmt) {
        return null;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return $result;
}

/**
 * Check if email exists
 */
function email_exists($email, $exclude_user_id = null) {
    global $conn;
    
    $query = "SELECT user_id FROM users WHERE email = ?";
    $params = [$email];
    $param_types = "s";
    
    if ($exclude_user_id) {
        $query .= " AND user_id != ?";
        $params[] = $exclude_user_id;
        $param_types .= "i";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    return $result->num_rows > 0;
}

// ============================================
// VALIDATION HELPERS
// ============================================

/**
 * Validate email format
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indian format)
 */
function is_valid_phone($phone) {
    return preg_match('/^[6-9]\d{9}$/', preg_replace('/\D/', '', $phone));
}

/**
 * Validate password strength
 */
function is_strong_password($password) {
    // At least 8 chars, 1 uppercase, 1 lowercase, 1 number
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/\d/', $password);
}

/**
 * Sanitize input string
 */
function sanitize_input($input) {
    return trim(stripslashes($input));
}

// ============================================
// RESPONSE HELPERS
// ============================================

/**
 * Send JSON response (shorthand)
 */
function send_json($success, $message = '', $data = null, $code = 200) {
    json_response($success, $message, $data, $code);
}

/**
 * Send error response
 */
function send_error($message, $code = 400, $data = null) {
    json_response(false, $message, $data, $code);
}

/**
 * Send success response
 */
function send_success($message = 'Success', $data = null, $code = 200) {
    json_response(true, $message, $data, $code);
}

// ============================================
// UTILITY HELPERS
// ============================================

/**
 * Get base URL
 */
function base_url($path = '') {
    return SITE_URL . ltrim($path, '/');
}

/**
 * Get uploaded image URL
 */
function image_url($filename) {
    return UPLOADS_URL . $filename;
}

/**
 * Truncate text
 */
function truncate($text, $length = 100) {
    return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
}

/**
 * Time ago format
 */
function time_ago($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return date('M d, Y', $time);
    }
}

?>
