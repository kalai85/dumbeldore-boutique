<?php require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <?php
    
    $error = '';
    $success = '';
    
    // Get categories
    $categories = $conn->query("SELECT * FROM categories WHERE status = 'active'");
    
    // Handle product add
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_name = trim($_POST['product_name'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $discount_percent = intval($_POST['discount_percent'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $material = trim($_POST['material'] ?? '');
        $color = trim($_POST['color'] ?? '');
        $sizes = trim($_POST['sizes'] ?? '');
        $stock = intval($_POST['stock'] ?? 0);
        
        if (empty($product_name) || $category_id <= 0 || $price <= 0) {
            $error = 'Please fill all required fields correctly';
        } else {
            $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, price, discount_percent, description, material, color, sizes, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sidisssi", $product_name, $category_id, $price, $discount_percent, $description, $material, $color, $sizes, $stock);
            
            if ($stmt->execute()) {
                $success = 'Product added successfully!';
            } else {
                $error = 'Error adding product';
            }
            $stmt->close();
        }
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
                    <a href="products.php" class="admin-nav-link">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <a href="add-product.php" class="admin-nav-link active">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </a>
                    <a href="orders.php" class="admin-nav-link">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <a href="logout.php" class="admin-nav-link text-danger mt-5">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px;">
                <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                    <i class="fas fa-plus-circle"></i> Add New Product
                </h1>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 admin-card">
                            <div class="card-body p-5">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label text-light fw-bold">Product Name *</label>
                                        <input type="text" class="form-control" name="product_name" required style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-light fw-bold">Category *</label>
                                            <select class="form-select" name="category_id" required style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                                <option value="">Select Category</option>
                                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                                <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-light fw-bold">Price (₹) *</label>
                                            <input type="number" class="form-control" name="price" step="0.01" required style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-light fw-bold">Discount %</label>
                                            <input type="number" class="form-control" name="discount_percent" min="0" max="100" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-light fw-bold">Stock Quantity</label>
                                            <input type="number" class="form-control" name="stock" min="0" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-light fw-bold">Description</label>
                                        <textarea class="form-control" name="description" rows="3" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;"></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-light fw-bold">Material</label>
                                            <input type="text" class="form-control" name="material" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-light fw-bold">Color</label>
                                            <input type="text" class="form-control" name="color" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-light fw-bold">Available Sizes</label>
                                        <input type="text" class="form-control" name="sizes" placeholder="e.g., S,M,L,XL" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                    </div>

                                    <button type="submit" class="btn fw-bold w-100" style="background: #d4af37; color: #0a0e27; padding: 12px;">
                                        <i class="fas fa-save"></i> Add Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
