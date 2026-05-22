<?php require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <?php
    
    // Get dashboard statistics
    $totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
    $totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
    $totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
    $totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'delivered'")->fetch_assoc()['total'] ?? 0;
    
    // Recent orders
    $recentOrders = $conn->query("SELECT o.*, u.fullname, u.email FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.created_at DESC LIMIT 5");
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-crown"></i> DUMBLEDORE ADMIN
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item">
                        <span class="nav-link text-light">Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; border-right: 1px solid rgba(212, 175, 55, 0.2);">
                <div class="nav flex-column p-3">
                    <a href="dashboard.php" class="nav-link text-light fw-bold mb-2" style="color: #d4af37;">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="products.php" class="nav-link text-light mb-2">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <a href="add-product.php" class="nav-link text-light mb-2">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </a>
                    <a href="orders.php" class="nav-link text-light mb-2">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <a href="users.php" class="nav-link text-light mb-2">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="categories.php" class="nav-link text-light mb-2">
                        <i class="fas fa-list"></i> Categories
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px;">
                <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </h1>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total Users</p>
                                        <h3 class="text-light fw-bold" style="color: #d4af37;"><?php echo $totalUsers; ?></h3>
                                    </div>
                                    <i class="fas fa-users" style="font-size: 32px; color: #d4af37; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total Products</p>
                                        <h3 class="text-light fw-bold" style="color: #d4af37;"><?php echo $totalProducts; ?></h3>
                                    </div>
                                    <i class="fas fa-box" style="font-size: 32px; color: #d4af37; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total Orders</p>
                                        <h3 class="text-light fw-bold" style="color: #d4af37;"><?php echo $totalOrders; ?></h3>
                                    </div>
                                    <i class="fas fa-shopping-bag" style="font-size: 32px; color: #d4af37; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total Revenue</p>
                                        <h3 class="text-warning fw-bold">₹<?php echo number_format($totalRevenue, 2); ?></h3>
                                    </div>
                                    <i class="fas fa-rupee-sign" style="font-size: 32px; color: #d4af37; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                            <i class="fas fa-shopping-bag"></i> Recent Orders
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr style="border-color: rgba(212, 175, 55, 0.2);">
                                        <th style="color: #d4af37;">Order ID</th>
                                        <th style="color: #d4af37;">Customer</th>
                                        <th style="color: #d4af37;">Amount</th>
                                        <th style="color: #d4af37;">Status</th>
                                        <th style="color: #d4af37;">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $recentOrders->fetch_assoc()): ?>
                                    <tr style="border-color: rgba(212, 175, 55, 0.1);">
                                        <td>#<?php echo $order['order_id']; ?></td>
                                        <td><?php echo $order['fullname']; ?></td>
                                        <td><span style="color: #d4af37;">₹<?php echo number_format($order['total_amount'], 2); ?></span></td>
                                        <td><span class="badge" style="background: #ffc107; color: #000;"><?php echo ucfirst($order['order_status']); ?></span></td>
                                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
