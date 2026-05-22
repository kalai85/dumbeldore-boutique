<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $order_id = intval($_GET['order_id'] ?? 0);
    
    if ($order_id <= 0) {
        header('Location: orders.php');
        exit();
    }
    
    // Get order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$order) {
        header('Location: orders.php');
        exit();
    }
    
    // Get order items
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $itemsResult = $stmt->get_result();
    $orderItems = [];
    while ($item = $itemsResult->fetch_assoc()) {
        $orderItems[] = $item;
    }
    $stmt->close();
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE
            </a>
        </div>
    </nav>

    <!-- Order Details Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                <i class="fas fa-receipt"></i> Order Details
            </h1>

            <div class="row g-4">
                <!-- Order Information -->
                <div class="col-lg-7">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body p-5">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                Order Information
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="text-light mb-2"><strong>Order ID:</strong></p>
                                    <p class="text-warning" style="font-size: 18px;">#<?php echo $order['order_id']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-light mb-2"><strong>Order Date:</strong></p>
                                    <p class="text-light"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                                </div>
                            </div>

                            <div class="mb-4 pb-4" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                <p class="text-light mb-2"><strong>Order Status:</strong></p>
                                <span class="badge" style="background: <?php echo $order['order_status'] == 'delivered' ? '#51cf66' : ($order['order_status'] == 'shipped' ? '#4dabf7' : '#ffc107'); ?>; font-size: 14px; padding: 10px 15px;">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </div>

                            <!-- Shipping Address -->
                            <h6 class="text-light fw-bold mb-3" style="color: #d4af37;">Shipping Address</h6>
                            <p class="text-light mb-0">
                                <?php echo $order['shipping_address']; ?><br>
                                <?php echo $order['shipping_city']; ?><br>
                                <i class="fas fa-phone"></i> <?php echo $order['shipping_phone']; ?>
                            </p>

                            <!-- Order Items -->
                            <h6 class="text-light fw-bold mt-4 mb-3" style="color: #d4af37;">Items Ordered</h6>
                            <div>
                                <?php foreach ($orderItems as $item): ?>
                                <div class="d-flex justify-content-between align-items-center text-light mb-3 pb-2" style="border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                    <div>
                                        <p class="mb-0"><strong><?php echo $item['product_name']; ?></strong></p>
                                        <small class="text-muted">Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['product_price'], 2); ?></small>
                                    </div>
                                    <p class="mb-0"><strong style="color: #d4af37;">₹<?php echo number_format($item['item_total'], 2); ?></strong></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; position: sticky; top: 100px;">
                        <div class="card-body p-5">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                Order Summary
                            </h5>

                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-light">Subtotal:</span>
                                <span class="text-light">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>

                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-light">Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>

                            <div class="mb-4 d-flex justify-content-between fw-bold" style="border-top: 1px solid rgba(212, 175, 55, 0.2); padding-top: 15px;">
                                <span class="text-light">Total Amount:</span>
                                <span style="color: #d4af37; font-size: 18px;">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>

                            <div class="mb-4 pb-4" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                <p class="text-light mb-2"><strong>Payment Method:</strong></p>
                                <p class="text-muted"><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>
                            </div>

                            <a href="orders.php" class="btn fw-bold w-100" style="background: #d4af37; color: #0a0e27; border-radius: 8px;">
                                <i class="fas fa-chevron-left"></i> Back to Orders
                            </a>

                            <button class="btn fw-bold w-100 mt-2" onclick="window.print()" style="border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;">
                                <i class="fas fa-print"></i> Print Order
                            </button>
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
