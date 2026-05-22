<?php
/**
 * Enhanced Home Page - MODERNIZED
 * DUMBLEDORE BOUTIQUE
 */

require_once 'php/config_enhanced.php';
require_once 'php/helpers.php';

// Redirect to login if not authenticated
if (!is_logged_in()) {
    redirect(base_url('login.php'));
}

$current_user_id = get_current_user_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMBLEDORE BOUTIQUE - Premium Luxury Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
            --primary-gold: #d4af37;
            --primary-dark: #0a0e27;
            --secondary-dark: #1a1a3a;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1a1a3a 50%, var(--primary-dark) 100%);
            color: #fff;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
            padding: 60px 20px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-content h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-gold) 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease-out;
        }
        
        .hero-content p {
            font-size: 1.1rem;
            color: #b0b0b0;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        .btn-gold {
            background: linear-gradient(135deg, var(--primary-gold) 0%, #f0e68c 100%);
            border: none;
            color: var(--primary-dark);
            font-weight: 700;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }
        
        .product-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-gold);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15);
        }
        
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php require_once 'php/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Welcome to Luxury Shopping</h1>
                    <p>Discover premium products hand-picked for discerning customers. Experience the magic of exclusive collections.</p>
                    <div class="d-flex gap-3">
                        <a href="<?php echo base_url('products.php'); ?>" class="btn btn-gold">
                            <i class="fas fa-shopping-bags"></i> Shop Now
                        </a>
                        <button class="btn btn-outline-light" onclick="document.getElementById('featured').scrollIntoView({behavior:'smooth'})">
                            <i class="fas fa-arrow-down"></i> Explore
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div style="font-size: 120px; opacity: 0.1;">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5" id="featured" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%);">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--primary-gold); font-weight: 700; font-size: 2.5rem;" data-aos="fade-up">
                <i class="fas fa-sparkles"></i> Featured Products
            </h2>

            <div class="row g-4">
                <?php
                $result = get_products(null, '', 'latest', 1, 6);
                
                if (!empty($result['products'])) {
                    foreach ($result['products'] as $product) {
                        $final_price = get_final_price($product['price'], $product['discount_percent']);
                        ?>
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                            <div class="product-card h-100">
                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    <img src="<?php echo h($product['product_image']); ?>" 
                                         alt="<?php echo h($product['product_name']); ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                    
                                    <?php if ($product['discount_percent'] > 0): ?>
                                        <span class="position-absolute top-3 end-3 badge" style="background: #d4af37; color: #0a0e27;">
                                            <?php echo intval($product['discount_percent']); ?>% OFF
                                        </span>
                                    <?php endif; ?>

                                    <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(180deg, transparent, rgba(0,0,0,0.8));">
                                        <a href="<?php echo base_url('product-details.php?id=' . intval($product['product_id'])); ?>" class="btn btn-sm btn-gold w-100">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>

                                <div class="p-3">
                                    <h6 class="mb-2">
                                        <a href="<?php echo base_url('product-details.php?id=' . intval($product['product_id'])); ?>" style="color: #fff; text-decoration: none;">
                                            <?php echo h($product['product_name']); ?>
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-3"><?php echo h($product['color']); ?></p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span style="color: var(--primary-gold); font-weight: 700; font-size: 1.2rem;">
                                                <?php echo format_price($final_price); ?>
                                            </span>
                                            <?php if ($product['discount_percent'] > 0): ?>
                                                <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.9rem;">
                                                    <?php echo format_price($product['price']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center text-muted"><p>No products available</p></div>';
                }
                ?>
            </div>

            <div class="text-center mt-5">
                <a href="<?php echo base_url('products.php'); ?>" class="btn btn-gold btn-lg">
                    <i class="fas fa-shopping-bags"></i> View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background: rgba(10, 14, 39, 0.95);">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="p-4">
                        <i class="fas fa-truck" style="font-size: 2.5rem; color: var(--primary-gold);"></i>
                        <h5 class="mt-3 mb-2" style="color: var(--primary-gold);">Fast Delivery</h5>
                        <p class="text-muted">Quick and reliable shipping to your doorstep</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-4">
                        <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: var(--primary-gold);"></i>
                        <h5 class="mt-3 mb-2" style="color: var(--primary-gold);">Secure Payment</h5>
                        <p class="text-muted">100% safe and encrypted transactions</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-4">
                        <i class="fas fa-undo" style="font-size: 2.5rem; color: var(--primary-gold);"></i>
                        <h5 class="mt-3 mb-2" style="color: var(--primary-gold);">Easy Returns</h5>
                        <p class="text-muted">Hassle-free returns within 30 days</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(15, 15, 30, 0.95) 100%); border-top: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container py-5">
            <div class="text-center text-muted">
                <p>&copy; 2024-2026 DUMBLEDORE BOUTIQUE. All rights reserved.</p>
                <p style="font-size: 0.9rem;">Premium Luxury Store | Secure Checkout | Fast Delivery</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            mirror: false
        });
    </script>
</body>
</html>
