<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $product_id = intval($_GET['id'] ?? 0);
    
    if ($product_id <= 0) {
        header('Location: products.php');
        exit();
    }
    
    // Get product details
    $stmt = $conn->prepare("SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = ? AND p.status = 'active'");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$product) {
        header('Location: products.php');
        exit();
    }
    
    // Calculate final price
    $finalPrice = $product['price'] - ($product['price'] * $product['discount_percent'] / 100);
    
    // Get reviews
    $stmt = $conn->prepare("SELECT r.*, u.fullname FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ? ORDER BY r.created_at DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $reviewsResult = $stmt->get_result();
    $reviews = [];
    while ($review = $reviewsResult->fetch_assoc()) {
        $reviews[] = $review;
    }
    $stmt->close();
    
    // Get cart count
    $cartCount = 0;
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $cartResult = $stmt->get_result()->fetch_assoc();
    $cartCount = $cartResult['total'] ?? 0;
    $stmt->close();
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
                        <a class="nav-link text-light" href="products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-bag"></i> Cart
                            <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $cartCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Details Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <div class="row g-4">
                <!-- Product Image -->
                <div class="col-lg-6">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; height: 500px;">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas fa-image" style="font-size: 150px; color: #d4af37; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="dashboard.php" class="text-light text-decoration-none">Home</a></li>
                                <li class="breadcrumb-item"><a href="products.php" class="text-light text-decoration-none">Products</a></li>
                                <li class="breadcrumb-item active" style="color: #d4af37;"><?php echo $product['product_name']; ?></li>
                            </ol>
                        </nav>

                        <!-- Title -->
                        <h1 class="text-light fw-bold mb-2"><?php echo $product['product_name']; ?></h1>
                        <p class="text-muted mb-3"><?php echo $product['category_name']; ?></p>

                        <!-- Rating -->
                        <div class="mb-3">
                            <?php 
                            $rating = intval($product['rating']);
                            for ($i = 0; $i < 5; $i++) {
                                echo $i < $rating ? '<i class="fas fa-star" style="color: #d4af37; font-size: 16px;"></i>' : '<i class="fas fa-star" style="color: rgba(212, 175, 55, 0.3); font-size: 16px;"></i>';
                            }
                            ?>
                            <span class="text-muted ms-2">(<?php echo count($reviews); ?> reviews)</span>
                        </div>

                        <!-- Price -->
                        <div class="mb-4">
                            <span class="h2 fw-bold text-warning">₹<?php echo number_format($finalPrice, 2); ?></span>
                            <?php if ($product['discount_percent'] > 0): ?>
                            <span class="text-muted text-decoration-line-through ms-3" style="font-size: 20px;">₹<?php echo number_format($product['price'], 2); ?></span>
                            <span class="badge" style="background: #d4af37; color: #0a0e27;">Save <?php echo $product['discount_percent']; ?>%</span>
                            <?php endif; ?>
                        </div>

                        <!-- Details -->
                        <div class="card border-0 mb-4" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 10px;">
                            <div class="card-body">
                                <h6 class="text-light fw-bold mb-3">Product Details</h6>
                                <p class="text-light mb-2"><strong>Material:</strong> <?php echo $product['material'] ?? 'Not specified'; ?></p>
                                <p class="text-light mb-2"><strong>Color:</strong> <?php echo $product['color']; ?></p>
                                <p class="text-light mb-2"><strong>Available Sizes:</strong> <?php echo $product['sizes']; ?></p>
                                <p class="text-light mb-2"><strong>Stock:</strong> <?php echo $product['stock'] > 0 ? '<span style="color: #51cf66;">In Stock</span>' : '<span style="color: #ff6b6b;">Out of Stock</span>'; ?></p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h6 class="text-light fw-bold mb-2">Description</h6>
                            <p class="text-light"><?php echo $product['description']; ?></p>
                        </div>

                        <!-- Add to Cart -->
                        <?php if ($product['stock'] > 0): ?>
                        <form method="POST" action="php/add_to_cart.php" class="mb-4">
                            <div class="row g-2 align-items-end">
                                <div class="col-auto">
                                    <label class="text-light fw-bold">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white; width: 80px;">
                                </div>
                                <div class="col flex-grow-1">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <button type="submit" class="btn fw-bold w-100" style="background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); color: #0a0e27; border-radius: 8px; padding: 10px;">
                                        <i class="fas fa-shopping-bag"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php else: ?>
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-circle"></i> Out of Stock
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="row mt-5">
                <div class="col-lg-8">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                Customer Reviews
                            </h5>

                            <?php if (empty($reviews)): ?>
                            <p class="text-muted text-center py-4">No reviews yet. Be the first to review!</p>
                            <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                            <div class="mb-4 pb-4" style="border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="text-light fw-bold mb-1"><?php echo $review['fullname']; ?></h6>
                                        <div>
                                            <?php 
                                            for ($i = 0; $i < 5; $i++) {
                                                echo $i < $review['rating'] ? '<i class="fas fa-star" style="color: #d4af37; font-size: 12px;"></i>' : '<i class="fas fa-star" style="color: rgba(212, 175, 55, 0.3); font-size: 12px;"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <small class="text-muted"><?php echo date('d M Y', strtotime($review['created_at'])); ?></small>
                                </div>
                                <p class="text-light mb-0"><?php echo $review['review_text']; ?></p>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
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
