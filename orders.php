<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    require_once 'php/auth_check.php';
    
    $user_id = $_SESSION['user_id'];
    
    // Get user orders
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $ordersResult = $stmt->get_result();
    $orders = [];
    
    while ($order = $ordersResult->fetch_assoc()) {
        // Get items count
        $itemStmt = $conn->prepare("SELECT COUNT(*) as item_count, SUM(quantity) as total_qty FROM order_items WHERE order_id = ?");
        $itemStmt->bind_param("i", $order['order_id']);
        $itemStmt->execute();
        $itemData = $itemStmt->get_result()->fetch_assoc();
        $order['item_count'] = $itemData['item_count'];
        $order['total_qty'] = $itemData['total_qty'];
        $itemStmt->close();
        
        $orders[] = $order;
    }
    $stmt->close();
    
    // Get status badge color
    function getStatusBadge($status) {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];
        return $badges[$status] ?? 'secondary';
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-light" href="orders.php">My Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Orders Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                <i class="fas fa-box"></i> My Orders
            </h1>

            <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center py-5" style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; color: #d4af37;">
                <i class="fas fa-inbox fa-2x mb-3"></i>
                <h4>No orders yet</h4>
                <p>Start shopping to place your first order</p>
                <a href="products.php" class="btn fw-bold mt-3" style="background: #d4af37; color: #0a0e27;">Start Shopping</a>
            </div>
            <?php else: ?>

            <div class="row g-4">
                <?php foreach ($orders as $order): ?>
                <div class="col-lg-6">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3 pb-3" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                <div>
                                    <h5 class="text-light fw-bold mb-1">Order #<?php echo $order['order_id']; ?></h5>
                                    <p class="text-muted small mb-0">Placed on <?php echo date('d M Y', strtotime($order['created_at'])); ?></p>
                                </div>
                                <span class="badge" style="background: <?php echo getStatusBadge($order['order_status']); ?>;">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="text-light mb-1"><strong><?php echo $order['item_count']; ?> Item(s)</strong></p>
                                <p class="text-muted small mb-0"><?php echo $order['total_qty']; ?> unit(s) total</p>
                            </div>

                            <div class="mb-3">
                                <p class="text-light mb-1"><strong>Delivery Address</strong></p>
                                <p class="text-muted small mb-0">
                                    <?php echo $order['shipping_city']; ?><br>
                                    <i class="fas fa-phone"></i> <?php echo $order['shipping_phone']; ?>
                                </p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0" style="color: #d4af37;">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                                <a href="order-details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-sm fw-bold" style="background: #d4af37; color: #0a0e27;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
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
