<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMBLEDORE BOUTIQUE - Home</title>
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
    ?>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <!-- Brand Logo -->
            <a class="navbar-brand fw-bold" href="dashboard.php" style="font-size: 24px; background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                <i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE
            </a>

            <!-- Hamburger Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-light fw-500" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-500" href="products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-500" href="#about">About</a>
                    </li>

                    <!-- Search Bar -->
                    <li class="nav-item w-100 w-lg-auto mt-3 mt-lg-0">
                        <form class="d-flex" action="search.php" method="GET">
                            <input class="form-control form-control-sm" type="search" name="q" placeholder="Search products..." style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                            <button class="btn btn-sm" type="submit" style="color: #d4af37;">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>

                    <!-- Icons -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="wishlist.php" title="Wishlist">
                            <i class="fas fa-heart fa-lg" style="color: #d4af37;"></i>
                            <?php if ($wishlistCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $wishlistCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php" title="Cart">
                            <i class="fas fa-shopping-bag fa-lg" style="color: #d4af37;"></i>
                            <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $cartCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light fw-500" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg" style="color: #d4af37;"></i> <?php echo substr($_SESSION['fullname'], 0, 1); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background: rgba(10, 14, 39, 0.95); border-color: #d4af37;">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="orders.php"><i class="fas fa-box"></i> My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.9) 0%, rgba(26, 26, 58, 0.9) 50%, rgba(15, 15, 30, 0.9) 100%), url('images/hero-bg.jpg'); background-size: cover; background-position: center; min-height: 600px; display: flex; align-items: center; justify-content: center;">
        
        <!-- Animated Background Elements -->
        <div class="position-absolute w-100 h-100" style="overflow: hidden;">
            <div class="position-absolute" style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%); border-radius: 50%; top: -100px; left: -100px; animation: float 8s ease-in-out infinite;"></div>
            <div class="position-absolute" style="width: 300px; height: 300px; background: radial-gradient(circle, rgba(240, 230, 140, 0.08) 0%, transparent 70%); border-radius: 50%; bottom: -50px; right: -50px; animation: float 10s ease-in-out infinite;"></div>
        </div>

        <!-- Content -->
        <div class="container position-relative z-index-1">
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-up">
                    <h1 class="display-4 fw-bold mb-4" style="color: #d4af37; text-shadow: 0 0 30px rgba(212, 175, 55, 0.3);">
                        Welcome to Luxury Fashion
                    </h1>
                    <p class="lead text-light mb-4" style="font-size: 18px;">
                        Discover premium handcrafted sarees, kurtis, and traditional wear that embodies elegance and timeless beauty.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="products.php" class="btn btn-lg fw-bold" style="background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); color: #0a0e27; border: none; border-radius: 50px;">
                            <i class="fas fa-shopping-bag"></i> Shop Now
                        </a>
                        <a href="#collections" class="btn btn-lg fw-bold" style="border: 2px solid #d4af37; color: #d4af37; background: transparent; border-radius: 50px;">
                            <i class="fas fa-chevron-down"></i> Explore
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center" data-aos="zoom-in">
                    <div style="font-size: 150px; opacity: 0.2;">
                        <i class="fas fa-crown" style="color: #d4af37;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="py-5" id="collections" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%);">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up" style="color: #d4af37; font-size: 42px; font-weight: 700;">
                <i class="fas fa-sparkles"></i> Our Collections
            </h2>

            <!-- Categories Grid -->
            <div class="row g-4">
                <?php
                // Fetch categories
                $result = $conn->query("SELECT * FROM categories WHERE status = 'active' LIMIT 8");
                
                if ($result->num_rows > 0) {
                    while ($category = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-6 col-lg-3" data-aos="zoom-in">
                            <a href="products.php?category=<?php echo $category['category_id']; ?>" class="text-decoration-none">
                                <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; transition: all 0.3s ease; cursor: pointer; height: 100%;">
                                    <div class="card-body text-center py-5" style="border-top: 2px solid #d4af37;">
                                        <div style="font-size: 50px; color: #d4af37; margin-bottom: 15px;">
                                            <i class="fas fa-dress"></i>
                                        </div>
                                        <h5 class="card-title text-light fw-bold mb-2"><?php echo $category['category_name']; ?></h5>
                                        <p class="text-muted small mb-0"><?php echo substr($category['category_description'], 0, 50); ?>...</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5" style="background: rgba(10, 14, 39, 0.8);">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up" style="color: #d4af37; font-size: 42px; font-weight: 700;">
                <i class="fas fa-star"></i> Featured Products
            </h2>

            <div class="row g-4">
                <?php
                // Fetch featured products
                $result = $conn->query("SELECT * FROM products WHERE status = 'active' ORDER BY rating DESC LIMIT 8");
                
                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        $finalPrice = $product['price'] - ($product['price'] * $product['discount_percent'] / 100);
                        ?>
                        <div class="col-md-6 col-lg-3" data-aos="fade-up">
                            <div class="card border-0 h-100" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; position: relative; transition: all 0.3s ease;">
                                
                                <!-- Image Container -->
                                <div class="position-relative overflow-hidden" style="height: 250px; background: rgba(212, 175, 55, 0.1);">
                                    <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" style="width:100%; height:250px; object-fit:cover;">
                                    <!-- Discount Badge -->
                                    <?php if ($product['discount_percent'] > 0): ?>
                                    <span class="position-absolute top-3 end-3 badge" style="background: #d4af37; color: #0a0e27; font-size: 12px;">
                                        <?php echo $product['discount_percent']; ?>% OFF
                                    </span>
                                    <?php endif; ?>

                                    <!-- Quick Buttons -->
                                    <div class="position-absolute bottom-3 start-50 translate-middle-x w-100 px-2">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-sm fw-bold" style="background: #d4af37; color: #0a0e27; border: none; border-radius: 8px;" data-product="<?php echo $product['product_id']; ?>" onclick="viewProduct(this)">
                                                <i class="fas fa-eye"></i> Quick View
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body pb-2">
                                    <h6 class="card-title text-light fw-bold mb-2 text-truncate"><?php echo $product['product_name']; ?></h6>
                                    <p class="text-muted small mb-3"><?php echo $product['color']; ?></p>

                                    <!-- Rating -->
                                    <div class="mb-3">
                                        <?php 
                                        $rating = intval($product['rating']);
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $rating) {
                                                echo '<i class="fas fa-star" style="color: #d4af37; font-size: 12px;"></i>';
                                            } else {
                                                echo '<i class="fas fa-star" style="color: rgba(212, 175, 55, 0.3); font-size: 12px;"></i>';
                                            }
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

                                <!-- Card Footer -->
                                <div class="card-footer border-0 bg-transparent">
                                    <div class="d-grid gap-2">
                                        <form method="POST" action="php/add_to_cart.php">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm fw-bold w-100" style="background: #d4af37; color: #0a0e27; border: none; border-radius: 8px;">
                                                <i class="fas fa-shopping-bag"></i> Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                    <button class="btn btn-sm w-100 mt-2 fw-bold" style="border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">
                                        <i class="far fa-heart"></i> Wishlist
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div class="text-center mt-5">
                <a href="products.php" class="btn btn-lg fw-bold" style="background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); color: #0a0e27; border: none; border-radius: 50px;">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/919876543210?text=Hello%20DUMBLEDORE%20BOUTIQUE" target="_blank" class="whatsapp-btn" style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 30px; z-index: 1000; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); transition: all 0.3s ease;">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(15, 15, 30, 0.95) 100%); border-top: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5 style="color: #d4af37; font-weight: 700;">About Us</h5>
                    <p class="text-muted small">DUMBLEDORE BOUTIQUE offers premium, handcrafted traditional and modern fashion wear for discerning customers.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 style="color: #d4af37; font-weight: 700;">Quick Links</h5>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-muted text-decoration-none">Shop</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 style="color: #d4af37; font-weight: 700;">Support</h5>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Returns</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Shipping</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 style="color: #d4af37; font-weight: 700;">Contact</h5>
                    <p class="text-muted small">
                        <i class="fas fa-phone"></i> +91 98765 43210<br>
                        <i class="fas fa-envelope"></i> info@dumbledore.com
                    </p>
                </div>
            </div>
            <hr style="border-color: rgba(212, 175, 55, 0.2);">
            <div class="text-center text-muted small">
                <p>&copy; 2024 DUMBLEDORE BOUTIQUE. All rights reserved. | Premium Luxury Fashion</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="js/script.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out'
        });

        function viewProduct(btn) {
            const productId = btn.getAttribute('data-product');
            window.location.href = 'product-details.php?id=' + productId;
        }

        function addToWishlist(productId) {
            fetch('php/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Added to Wishlist!');
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
