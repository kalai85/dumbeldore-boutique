<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $order_id = intval($_GET['order_id'] ?? 0);
    
    if ($order_id <= 0) {
        header('Location: dashboard.php');
        exit();
    }
    
    // Get order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$order) {
        header('Location: dashboard.php');
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

    <!-- Confirmation Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0; display: flex; align-items: center; justify-content: center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 text-center" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body py-5">
                            <!-- Success Icon -->
                            <div class="mb-4">
                                <i class="fas fa-check-circle" style="font-size: 80px; color: #51cf66;"></i>
                            </div>

                            <!-- Success Message -->
                            <h1 class="mb-3" style="color: #d4af37; font-weight: 700;">Order Confirmed!</h1>
                            <p class="text-light mb-4">Thank you for your purchase</p>

                            <!-- Order Details -->
                            <div class="text-start">
                                <div class="mb-4 pb-4" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                    <h5 style="color: #d4af37; font-weight: 700;">Order Details</h5>
                                    <p class="text-light mb-2"><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
                                    <p class="text-light mb-2"><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                                    <p class="text-light mb-2"><strong>Total Amount:</strong> <span style="color: #d4af37;">₹<?php echo number_format($order['total_amount'], 2); ?></span></p>
                                    <p class="text-light mb-2"><strong>Status:</strong> <span class="badge" style="background: #ffc107; color: #0a0e27;">Pending</span></p>
                                </div>

                                <!-- Shipping Address -->
                                <div class="mb-4 pb-4" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                    <h5 style="color: #d4af37; font-weight: 700;">Shipping Address</h5>
                                    <p class="text-light mb-0">
                                        <?php echo $order['shipping_address']; ?><br>
                                        <?php echo $order['shipping_city']; ?><br>
                                        <i class="fas fa-phone"></i> <?php echo $order['shipping_phone']; ?>
                                    </p>
                                </div>

                                <!-- Order Items -->
                                <div>
                                    <h5 style="color: #d4af37; font-weight: 700; margin-bottom: 15px;">Items Ordered</h5>
                                    <?php foreach ($orderItems as $item): ?>
                                    <div class="d-flex justify-content-between text-light mb-3 pb-2" style="border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
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

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <a href="orders.php" class="btn btn-lg fw-bold mx-2" style="background: #d4af37; color: #0a0e27; border-radius: 8px;">
                            <i class="fas fa-box"></i> View Orders
                        </a>
                        <a href="dashboard.php" class="btn btn-lg fw-bold mx-2" style="border: 2px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;">
                            <i class="fas fa-home"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(15, 15, 30, 0.95) 100%); border-top: 1px solid rgba(212, 175, 55, 0.2); margin-top: 40px;">
        <div class="container py-4">
            <div class="text-center text-muted small">
                <p>&copy; 2024 DUMBLEDORE BOUTIQUE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
