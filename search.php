<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $searchQuery = trim($_GET['q'] ?? '');
    $products = [];
    
    if (!empty($searchQuery)) {
        $searchTerm = "%$searchQuery%";
        $stmt = $conn->prepare("SELECT * FROM products WHERE (product_name LIKE ? OR color LIKE ? OR material LIKE ?) AND status = 'active' LIMIT 20");
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($product = $result->fetch_assoc()) {
            $products[] = $product;
        }
        $stmt->close();
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

    <!-- Search Results Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-1" style="color: #d4af37; font-weight: 700;">
                <i class="fas fa-search"></i> Search Results
            </h1>
            <p class="text-muted mb-5">Results for: <strong><?php echo htmlspecialchars($searchQuery); ?></strong></p>

            <?php if (empty($products)): ?>
            <div class="alert alert-info text-center py-5" style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; color: #d4af37;">
                <i class="fas fa-inbox fa-2x mb-3"></i>
                <h4>No products found</h4>
                <p>Try searching with different keywords</p>
                <a href="products.php" class="btn fw-bold mt-3" style="background: #d4af37; color: #0a0e27;">View All Products</a>
            </div>
            <?php else: ?>

            <div class="row g-4 mb-5">
                <?php foreach ($products as $product): ?>
                <?php $finalPrice = $product['price'] - ($product['price'] * $product['discount_percent'] / 100); ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 h-100" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        
                        <div class="position-relative overflow-hidden" style="height: 250px; background: rgba(212, 175, 55, 0.1);">
                            <i class="fas fa-image" style="font-size: 60px; color: #d4af37; opacity: 0.5; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                            
                            <?php if ($product['discount_percent'] > 0): ?>
                            <span class="position-absolute top-3 end-3 badge" style="background: #d4af37; color: #0a0e27;">
                                <?php echo $product['discount_percent']; ?>% OFF
                            </span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title text-light fw-bold mb-2"><?php echo $product['product_name']; ?></h6>
                            <p class="text-muted small mb-3"><?php echo $product['color']; ?></p>

                            <div class="mb-3">
                                <span class="h5 fw-bold text-warning">₹<?php echo number_format($finalPrice, 2); ?></span>
                                <?php if ($product['discount_percent'] > 0): ?>
                                <span class="text-muted text-decoration-line-through ms-2" style="font-size: 14px;">₹<?php echo number_format($product['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-footer border-0 bg-transparent">
                            <a href="product-details.php?id=<?php echo $product['product_id']; ?>" class="btn fw-bold w-100" style="background: #d4af37; color: #0a0e27; border-radius: 8px;">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>

            <div class="text-center">
                <a href="products.php" class="btn fw-bold" style="border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;">
                    <i class="fas fa-chevron-left"></i> Back to Products
                </a>
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
