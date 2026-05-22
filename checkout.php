<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $user_id = $_SESSION['user_id'];
    
    // Get cart items
    $query = "SELECT c.*, p.product_name, p.price, p.discount_percent FROM cart c 
              JOIN products p ON c.product_id = p.product_id 
              WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cartResult = $stmt->get_result();
    
    $cartTotal = 0;
    $cartItems = [];
    
    while ($item = $cartResult->fetch_assoc()) {
        $item['final_price'] = $item['price'] - ($item['price'] * $item['discount_percent'] / 100);
        $item['item_total'] = $item['final_price'] * $item['quantity'];
        $cartTotal += $item['item_total'];
        $cartItems[] = $item;
    }
    $stmt->close();
    
    // Get user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Handle order submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $payment_method = $_POST['payment_method'] ?? 'cod';
        
        if (empty($phone) || empty($address) || empty($city)) {
            echo '<div class="alert alert-danger">All fields are required</div>';
        } else if (empty($cartItems)) {
            echo '<div class="alert alert-danger">Cart is empty</div>';
        } else {
            // Create order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, shipping_address, shipping_city, shipping_phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("idssss", $user_id, $cartTotal, $payment_method, $address, $city, $phone);
            
            if ($stmt->execute()) {
                $order_id = $conn->insert_id;
                
                // Add order items
                foreach ($cartItems as $item) {
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, item_total) VALUES (?, ?, ?, ?, ?, ?)");
                    $item_total = $item['final_price'] * $item['quantity'];
                    $stmt->bind_param("iisiid", $order_id, $item['product_id'], $item['product_name'], $item['final_price'], $item['quantity'], $item_total);
                    $stmt->execute();
                }
                
                // Clear cart
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                
                // Redirect to order confirmation
                header('Location: order-confirmation.php?order_id='.$order_id);
                exit();
            }
            $stmt->close();
        }
    }
    
    if (empty($cartItems)) {
        header('Location: cart.php');
        exit();
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE
            </a>
        </div>
    </nav>

    <!-- Checkout Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                <i class="fas fa-credit-card"></i> Checkout
            </h1>

            <div class="row g-4">
                <!-- Order Form -->
                <div class="col-lg-7">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="card-title text-light fw-bold mb-4">Shipping Details</h5>

                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Full Name</label>
                                    <input type="text" class="form-control" value="<?php echo $user['fullname']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $user['email']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" value="<?php echo $user['phone']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Address</label>
                                    <textarea class="form-control" name="address" rows="3" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" required><?php echo $user['address'] ?? ''; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">City</label>
                                    <input type="text" class="form-control" name="city" value="<?php echo $user['city'] ?? ''; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-light fw-bold">Payment Method</label>
                                    <select class="form-select" name="payment_method" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                        <option value="cod">Cash on Delivery</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="upi">UPI</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn fw-bold w-100" style="background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); color: #0a0e27; border: none; border-radius: 8px; padding: 12px;">
                                    <i class="fas fa-check"></i> Complete Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; position: sticky; top: 100px;">
                        <div class="card-body">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                Order Summary
                            </h5>

                            <?php foreach ($cartItems as $item): ?>
                            <div class="mb-3 pb-3" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-light"><?php echo $item['product_name']; ?></span>
                                    <span class="text-light">x<?php echo $item['quantity']; ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">₹<?php echo number_format($item['final_price'], 2); ?></span>
                                    <span class="text-warning">₹<?php echo number_format($item['item_total'], 2); ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-light">Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>

                            <div class="mb-3 d-flex justify-content-between fw-bold" style="border-top: 1px solid rgba(212, 175, 55, 0.2); padding-top: 15px;">
                                <span class="text-light">Total:</span>
                                <span style="color: #d4af37; font-size: 18px;">₹<?php echo number_format($cartTotal, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(15, 15, 30, 0.95) 100%); border-top: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container py-4">
            <div class="text-center text-muted small">
                <p>&copy; 2024 DUMBLEDORE BOUTIQUE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
