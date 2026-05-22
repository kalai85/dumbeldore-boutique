<?php require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categories - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <?php
    
    // Get all categories
    $result = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
    $categories = [];
    while ($category = $result->fetch_assoc()) {
        $categories[] = $category;
    }
    
    // Handle delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category_id'])) {
        $category_id = intval($_POST['delete_category_id']);
        $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        header('Location: categories.php');
        exit();
    }
    
    // Handle add category
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
        $category_name = trim($_POST['category_name']);
        $category_description = trim($_POST['category_description'] ?? '');
        
        if (!empty($category_name)) {
            $stmt = $conn->prepare("INSERT INTO categories (category_name, category_description) VALUES (?, ?)");
            $stmt->bind_param("ss", $category_name, $category_description);
            $stmt->execute();
            header('Location: categories.php');
            exit();
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
                    <a href="categories.php" class="admin-nav-link active">
                        <i class="fas fa-list"></i> Categories
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
                    <i class="fas fa-list"></i> Manage Categories
                </h1>

                <div class="row g-4 mb-5">
                    <!-- Add Category Form -->
                    <div class="col-lg-4">
                        <div class="card border-0 admin-card">
                            <div class="card-body p-4">
                                <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                    Add New Category
                                </h5>

                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Category Name *</label>
                                        <input type="text" class="form-control" name="category_name" required style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-light">Description</label>
                                        <textarea class="form-control" name="category_description" rows="3" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;"></textarea>
                                    </div>

                                    <button type="submit" class="btn fw-bold w-100" style="background: #d4af37; color: #0a0e27;">
                                        <i class="fas fa-plus"></i> Add Category
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Categories List -->
                    <div class="col-lg-8">
                        <div class="card border-0 admin-card">
                            <div class="card-body p-4">
                                <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                    All Categories
                                </h5>

                                <div class="row g-3">
                                    <?php foreach ($categories as $category): ?>
                                    <div class="col-md-6">
                                        <div class="card border-1" style="background: rgba(255,255,255,0.03); border-color: rgba(212, 175, 55, 0.3);">
                                            <div class="card-body">
                                                <h6 class="card-title text-light fw-bold"><?php echo $category['category_name']; ?></h6>
                                                <p class="text-muted small mb-3"><?php echo substr($category['category_description'], 0, 50); ?></p>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="delete_category_id" value="<?php echo $category['category_id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
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
