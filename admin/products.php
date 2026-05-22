<?php require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <?php
    
    // Get all products
    $result = $conn->query("SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id ORDER BY p.created_at DESC");
    $products = [];
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
    
    // Handle delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product_id'])) {
        $product_id = intval($_POST['delete_product_id']);
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        header('Location: products.php');
        exit();
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-crown"></i> DUMBLEDORE ADMIN
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 admin-sidebar">
                <div class="nav flex-column p-3">
                    <a href="dashboard.php" class="admin-nav-link">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="products.php" class="admin-nav-link active">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <a href="add-product.php" class="admin-nav-link">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </a>
                    <a href="orders.php" class="admin-nav-link">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <a href="users.php" class="admin-nav-link">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="logout.php" class="admin-nav-link text-danger mt-5">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px;">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h1 style="color: #d4af37; font-weight: 700;">
                        <i class="fas fa-box"></i> Manage Products
                    </h1>
                    <a href="add-product.php" class="btn fw-bold" style="background: #d4af37; color: #0a0e27;">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>

                <div class="card border-0 admin-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Discount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td><?php echo $product['category_name']; ?></td>
                                        <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                        <td><?php echo $product['stock']; ?></td>
                                        <td><?php echo $product['discount_percent']; ?>%</td>
                                        <td>
                                            <span class="badge" style="background: <?php echo $product['status'] == 'active' ? '#51cf66' : '#ff6b6b'; ?>;">
                                                <?php echo ucfirst($product['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit-product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="delete_product_id" value="<?php echo $product['product_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
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
