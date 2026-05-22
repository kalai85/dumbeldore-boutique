<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    // Get cart count
    $cartCount = 0;
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $cartResult = $stmt->get_result()->fetch_assoc();
    $cartCount = $cartResult['total'] ?? 0;
    $stmt->close();
    
    // Get wishlist count
    $wishlistCount = 0;
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $wishlistResult = $stmt->get_result()->fetch_assoc();
    $wishlistCount = $wishlistResult['total'] ?? 0;
    $stmt->close();
    
    // Filter parameters
    $category_filter = intval($_GET['category'] ?? 0);
    $sort = $_GET['sort'] ?? 'latest';
    $search = trim($_GET['search'] ?? '');
    
    // Build query
    $where = "WHERE p.status = 'active'";
    $params = [];
    $param_types = "";
    
    if ($category_filter > 0) {
        $where .= " AND p.category_id = ?";
        $params[] = $category_filter;
        $param_types .= "i";
    }
    
    if (!empty($search)) {
        $where .= " AND (p.product_name LIKE ? OR p.color LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $param_types .= "ss";
    }
    
    // Sort
    $order = "p.created_at DESC";
    if ($sort == 'price_low') {
        $order = "p.price ASC";
    } elseif ($sort == 'price_high') {
        $order = "p.price DESC";
    } elseif ($sort == 'rating') {
        $order = "p.rating DESC";
    }
    ?>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php" style="font-size: 24px; background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                <i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-light fw-500" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-500 active" href="products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="wishlist.php">
                            <i class="fas fa-heart fa-lg" style="color: #d4af37;"></i>
                            <?php if ($wishlistCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $wishlistCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-bag fa-lg" style="color: #d4af37;"></i>
                            <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $cartCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light fw-500" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg" style="color: #d4af37;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background: rgba(10, 14, 39, 0.95); border-color: #d4af37;">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Products Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-5" data-aos="fade-up" style="color: #d4af37; font-size: 42px; font-weight: 700;">
                <i class="fas fa-shopping-bags"></i> Our Products
            </h1>

            <div class="row g-4">
                <!-- Sidebar - Filters -->
                <div class="col-lg-3" data-aos="fade-right">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                <i class="fas fa-filter"></i> Filters
                            </h5>

                            <!-- Search -->
                            <form method="GET" action="" class="mb-4">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                <button type="submit" class="btn btn-sm w-100 mt-2 fw-bold" style="background: #d4af37; color: #0a0e27;">Search</button>
                            </form>

                            <!-- Categories -->
                            <div class="mb-4">
                                <h6 class="text-light fw-bold mb-3">Categories</h6>
                                <div class="list-group list-group-flush">
                                    <a href="products.php" class="list-group-item list-group-item-action <?php echo $category_filter == 0 ? 'active' : ''; ?>" style="background: transparent; color: #d4af37; border-color: rgba(212, 175, 55, 0.2);">
                                        All Products
                                    </a>
                                    <?php
                                    $categories = $conn->query("SELECT * FROM categories WHERE status = 'active'");
                                    while ($cat = $categories->fetch_assoc()) {
                                        $active = $category_filter == $cat['category_id'] ? 'active' : '';
                                        echo '<a href="products.php?category='.$cat['category_id'].'" class="list-group-item list-group-item-action '.$active.'" style="background: transparent; color: #d4af37; border-color: rgba(212, 175, 55, 0.2);">'.$cat['category_name'].'</a>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Sort -->
                            <div>
                                <h6 class="text-light fw-bold mb-3">Sort By</h6>
                                <select class="form-select form-select-sm" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" onchange="window.location.href='?sort='+this.value+'<?php echo $category_filter > 0 ? '&category='.$category_filter : ''; ?>'">
                                    <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest</option>
                                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                    <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Rating</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <div class="row g-4">
                        <?php
                        // Fetch products
                        $sql = "SELECT p.* FROM products p " . $where . " ORDER BY " . $order . " LIMIT 12";
                        
                        $stmt = $conn->prepare($sql);
                        if (!empty($params)) {
                            $stmt->bind_param($param_types, ...$params);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($product = $result->fetch_assoc()) {
                                $finalPrice = $product['price'] - ($product['price'] * $product['discount_percent'] / 100);
                                ?>
                                <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                                    <div class="card border-0 h-100" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; position: relative; transition: all 0.3s ease;">
                                        
                                        <!-- Image -->
                                        <div class="position-relative overflow-hidden" style="height: 250px; background: rgba(212, 175, 55, 0.1);">
                                           <img src="<?php echo $product['product_image']; ?>" 
                                                alt="<?php echo $product['product_name']; ?>" 
                                                style="width:100%; height:250px; object-fit:cover;">
                                            
                                            <?php if ($product['discount_percent'] > 0): ?>
                                            <span class="position-absolute top-3 end-3 badge" style="background: #d4af37; color: #0a0e27;">
                                                <?php echo $product['discount_percent']; ?>% OFF
                                            </span>
                                            <?php endif; ?>

                                            <div class="position-absolute bottom-3 start-50 translate-middle-x w-100 px-2">
                                                <button class="btn btn-sm fw-bold w-100" style="background: #d4af37; color: #0a0e27; border: none; border-radius: 8px;" onclick="window.location.href='product-details.php?id=<?php echo $product['product_id']; ?>'">
                                                    <i class="fas fa-eye"></i> View Details
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Details -->
                                        <div class="card-body">
                                            <h6 class="card-title text-light fw-bold mb-2"><?php echo $product['product_name']; ?></h6>
                                            <p class="text-muted small mb-3"><?php echo $product['color']; ?></p>

                                            <!-- Rating -->
                                            <div class="mb-3">
                                                <?php 
                                                $rating = intval($product['rating']);
                                                for ($i = 0; $i < 5; $i++) {
                                                    echo $i < $rating ? '<i class="fas fa-star" style="color: #d4af37; font-size: 12px;"></i>' : '<i class="fas fa-star" style="color: rgba(212, 175, 55, 0.3); font-size: 12px;"></i>';
                                                }
                                                ?>
                                            </div>

                                            <!-- Price -->
                                            <div class="mb-3">
                                                <span class="h5 fw-bold text-warning">₹<?php echo number_format($finalPrice, 2); ?></span>
                                                <?php if ($product['discount_percent'] > 0): ?>
                                                <span class="text-muted text-decoration-line-through ms-2" style="font-size: 14px;">₹<?php echo number_format($product['price'], 2); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="card-footer border-0 bg-transparent">
                                            <form method="POST" action="php/add_to_cart.php" class="d-grid">
                                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn fw-bold" style="background: #d4af37; color: #0a0e27; border: none; border-radius: 8px;">
                                                    <i class="fas fa-shopping-bag"></i> Add to Cart
                                                </button>
                                            </form>
                                            <button class="btn btn-sm w-100 mt-2" style="border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="col-12 text-center"><p class="text-light">No products found</p></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(15, 15, 30, 0.95) 100%); border-top: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container py-5">
            <div class="text-center text-muted small">
                <p>&copy; 2024 DUMBLEDORE BOUTIQUE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="js/script.js"></script>
    <script>
        AOS.init({duration: 800});
        
        function addToWishlist(productId) {
            fetch('php/add_to_wishlist.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
