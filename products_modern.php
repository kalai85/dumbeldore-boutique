<?php
/**
 * Enhanced Products Page - MODERNIZED
 * DUMBLEDORE BOUTIQUE
 */

require_once 'php/config_enhanced.php';
require_once 'php/helpers.php';

// Check authentication
if (!is_logged_in()) {
    redirect(base_url('login.php'));
}

$current_user_id = get_current_user_id();

// Get filter parameters (safe input handling)
$category_filter = intval($_GET['category'] ?? 0);
$sort = $_GET['sort'] ?? 'latest';
$search = sanitize_input($_GET['search'] ?? '');
$page = intval($_GET['page'] ?? 1);
$page = max(1, $page);

// Validate sort parameter
$valid_sorts = ['latest', 'price_low', 'price_high', 'rating'];
if (!in_array($sort, $valid_sorts)) {
    $sort = 'latest';
}

// Get products with pagination
$result = get_products($category_filter > 0 ? $category_filter : null, $search, $sort, $page, 12);
$products = $result['products'];
$total_pages = $result['total_pages'];
$current_page = $result['page'];
?>
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
    <style>
        :root {
            --primary-gold: #d4af37;
            --primary-dark: #0a0e27;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1a1a3a 50%, var(--primary-dark) 100%);
        }
        
        .product-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-gold);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15);
        }
        
        .filter-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 15px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php require_once 'php/navbar.php'; ?>

    <!-- Products Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <!-- Header -->
            <h1 class="mb-5" data-aos="fade-up" style="color: var(--primary-gold); font-size: 42px; font-weight: 700;">
                <i class="fas fa-shopping-bags"></i> Our Products
            </h1>

            <div class="row g-4">
                <!-- Sidebar - Filters -->
                <div class="col-lg-3" data-aos="fade-right">
                    <div class="filter-card">
                        <h5 style="color: var(--primary-gold); border-bottom: 2px solid var(--primary-gold); padding-bottom: 15px; margin-bottom: 20px;">
                            <i class="fas fa-filter"></i> Filters
                        </h5>

                        <!-- Search Form -->
                        <form method="GET" action="" class="mb-4">
                            <input type="hidden" name="category" value="<?php echo $category_filter; ?>">
                            <input type="hidden" name="sort" value="<?php echo h($sort); ?>">
                            <div class="input-group mb-2">
                                <input type="text" name="search" class="form-control" placeholder="Search..." 
                                       value="<?php echo h($search); ?>" 
                                       style="background: rgba(255,255,255,0.1); border: 1px solid var(--primary-gold); color: white;">
                                <button class="btn fw-bold" type="submit" style="background: var(--primary-gold); color: var(--primary-dark);">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 style="color: var(--primary-gold); font-weight: 700; margin-bottom: 12px;">Categories</h6>
                            <div class="list-group list-group-flush">
                                <a href="<?php echo base_url('products.php'); ?>" 
                                   class="list-group-item list-group-item-action <?php echo $category_filter == 0 ? 'active' : ''; ?>" 
                                   style="background: transparent; color: var(--primary-gold); border-color: rgba(212, 175, 55, 0.2);">
                                    All Products
                                </a>
                                <?php
                                $categories_query = "SELECT category_id, category_name FROM categories WHERE status = 'active' ORDER BY category_name";
                                $categories = $conn->query($categories_query);
                                
                                while ($cat = $categories->fetch_assoc()) {
                                    $active = $category_filter == $cat['category_id'] ? 'active' : '';
                                    $cat_url = base_url('products.php?category=' . intval($cat['category_id']));
                                    echo '<a href="' . $cat_url . '" class="list-group-item list-group-item-action ' . $active . '" 
                                            style="background: transparent; color: var(--primary-gold); border-color: rgba(212, 175, 55, 0.2);">
                                        ' . h($cat['category_name']) . '
                                    </a>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <h6 style="color: var(--primary-gold); font-weight: 700; margin-bottom: 12px;">Sort By</h6>
                            <select class="form-select form-select-sm" style="background: rgba(255,255,255,0.1); border: 1px solid var(--primary-gold); color: white;" 
                                    onchange="window.location.href='?sort='+this.value+'<?php echo $category_filter > 0 ? '&category='.$category_filter : ''; ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>'">
                                <option value="latest" <?php echo $sort === 'latest' ? 'selected' : ''; ?>>Latest</option>
                                <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Rating</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <?php if (!empty($products)): ?>
                        <div class="row g-4 mb-5">
                            <?php
                            foreach ($products as $product) {
                                $final_price = get_final_price($product['price'], $product['discount_percent']);
                                $rating = intval($product['rating'] ?? 0);
                                ?>
                                <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                                    <div class="product-card">
                                        <!-- Image Section -->
                                        <div class="position-relative overflow-hidden" style="height: 250px; background: rgba(212, 175, 55, 0.1);">
                                            <img src="<?php echo h($product['product_image']); ?>" 
                                                 alt="<?php echo h($product['product_name']); ?>" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                            
                                            <?php if ($product['discount_percent'] > 0): ?>
                                                <span class="position-absolute top-3 end-3 badge" style="background: var(--primary-gold); color: var(--primary-dark);">
                                                    <?php echo intval($product['discount_percent']); ?>% OFF
                                                </span>
                                            <?php endif; ?>

                                            <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(180deg, transparent, rgba(0,0,0,0.8));">
                                                <a href="<?php echo base_url('product-details.php?id=' . intval($product['product_id'])); ?>" 
                                                   class="btn btn-sm w-100 fw-bold" 
                                                   style="background: var(--primary-gold); color: var(--primary-dark); border: none; border-radius: 8px;">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Details -->
                                        <div class="p-3">
                                            <h6 class="text-light fw-bold mb-2">
                                                <?php echo h(truncate($product['product_name'], 30)); ?>
                                            </h6>
                                            <p class="text-muted small mb-3">
                                                <?php echo h($product['color']); ?>
                                            </p>

                                            <!-- Rating -->
                                            <div class="mb-3">
                                                <?php
                                                for ($i = 0; $i < 5; $i++) {
                                                    $color = $i < $rating ? 'var(--primary-gold)' : 'rgba(212, 175, 55, 0.3)';
                                                    echo '<i class="fas fa-star" style="color: ' . $color . '; font-size: 12px;"></i>';
                                                }
                                                ?>
                                            </div>

                                            <!-- Price -->
                                            <div class="mb-3">
                                                <span class="h5 fw-bold" style="color: var(--primary-gold);">
                                                    <?php echo format_price($final_price); ?>
                                                </span>
                                                <?php if ($product['discount_percent'] > 0): ?>
                                                    <span class="text-muted text-decoration-line-through ms-2" style="font-size: 14px;">
                                                        <?php echo format_price($product['price']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Action Buttons -->
                                            <form method="POST" action="<?php echo base_url('php/add_to_cart.php'); ?>" class="d-grid gap-2">
                                                <input type="hidden" name="product_id" value="<?php echo intval($product['product_id']); ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn fw-bold" style="background: var(--primary-gold); color: var(--primary-dark); border: none; border-radius: 8px;">
                                                    <i class="fas fa-shopping-bag"></i> Add to Cart
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm w-100 mt-2" 
                                                    style="border: 1px solid var(--primary-gold); color: var(--primary-gold); background: transparent; border-radius: 8px;"
                                                    onclick="addToWishlist(<?php echo intval($product['product_id']); ?>)">
                                                <i class="far fa-heart"></i> Wishlist
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <nav aria-label="Page navigation" class="mb-5">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    // Previous Button
                                    if ($current_page > 1) {
                                        $prev_url = base_url('products.php?page=' . ($current_page - 1));
                                        if ($category_filter > 0) $prev_url .= '&category=' . $category_filter;
                                        if (!empty($search)) $prev_url .= '&search=' . urlencode($search);
                                        if ($sort !== 'latest') $prev_url .= '&sort=' . $sort;
                                        echo '<li class="page-item"><a class="page-link" href="' . $prev_url . '"><i class="fas fa-chevron-left"></i> Previous</a></li>';
                                    }

                                    // Page Numbers
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        $page_url = base_url('products.php?page=' . $i);
                                        if ($category_filter > 0) $page_url .= '&category=' . $category_filter;
                                        if (!empty($search)) $page_url .= '&search=' . urlencode($search);
                                        if ($sort !== 'latest') $page_url .= '&sort=' . $sort;
                                        
                                        if ($i === $current_page) {
                                            echo '<li class="page-item active"><span class="page-link" style="background: var(--primary-gold); border-color: var(--primary-gold);">' . $i . '</span></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="' . $page_url . '">' . $i . '</a></li>';
                                        }
                                    }

                                    // Next Button
                                    if ($current_page < $total_pages) {
                                        $next_url = base_url('products.php?page=' . ($current_page + 1));
                                        if ($category_filter > 0) $next_url .= '&category=' . $category_filter;
                                        if (!empty($search)) $next_url .= '&search=' . urlencode($search);
                                        if ($sort !== 'latest') $next_url .= '&sort=' . $sort;
                                        echo '<li class="page-item"><a class="page-link" href="' . $next_url . '">Next <i class="fas fa-chevron-right"></i></a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-search" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="mt-3">No products found. Try adjusting your filters.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(15, 15, 30, 0.95) 100%); border-top: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container py-4">
            <div class="text-center text-muted small">
                <p>&copy; 2024-2026 DUMBLEDORE BOUTIQUE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        function addToWishlist(productId) {
            fetch('<?php echo base_url('php/add_to_wishlist.php'); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Added to wishlist!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
