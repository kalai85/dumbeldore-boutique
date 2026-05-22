<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $user_id = $_SESSION['user_id'];
    
    // Get wishlist items
   $query = "SELECT w.*, p.product_name, p.product_image, p.price, p.discount_percent, p.rating 
          FROM wishlist w 
          JOIN products p ON w.product_id = p.product_id 
          WHERE w.user_id = ? AND p.status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $wishlistResult = $stmt->get_result();
    $wishlistItems = [];
    
    while ($item = $wishlistResult->fetch_assoc()) {
        $item['final_price'] = $item['price'] - ($item['price'] * $item['discount_percent'] / 100);
        $wishlistItems[] = $item;
    }
    $stmt->close();
    
    // Handle remove from wishlist
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_wishlist_id'])) {
        $wishlist_id = intval($_POST['remove_wishlist_id']);
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE wishlist_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $wishlist_id, $user_id);
        $stmt->execute();
        header('Location: wishlist.php');
        exit();
    }
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
                        <a class="nav-link text-light" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-light" href="wishlist.php">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Wishlist Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                <i class="fas fa-heart"></i> My Wishlist
            </h1>

            <?php if (empty($wishlistItems)): ?>
            <div class="alert alert-info text-center py-5" style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; color: #d4af37;">
                <i class="fas fa-heart fa-2x mb-3"></i>
                <h4>Your wishlist is empty</h4>
                <p>Add items to your wishlist to save them for later</p>
                <a href="products.php" class="btn fw-bold mt-3" style="background: #d4af37; color: #0a0e27;">Start Shopping</a>
            </div>
            <?php else: ?>

            <div class="row g-4">
                <?php foreach ($wishlistItems as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 h-100" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="position-relative overflow-hidden" style="height: 250px; background: rgba(212, 175, 55, 0.1);">
                            <img src="<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>" style="width:100%; height:250px; object-fit:cover;">
                            
                            <form method="POST" style="position: absolute; top: 10px; right: 10px;">
                                <input type="hidden" name="remove_wishlist_id" value="<?php echo $item['wishlist_id']; ?>">
                                <button type="submit" class="btn btn-sm" style="background: #d4af37; color: #0a0e27;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title text-light fw-bold mb-2"><?php echo $item['product_name']; ?></h6>
                            
                            <!-- Rating -->
                            <div class="mb-2">
                                <?php 
                                $rating = intval($item['rating']);
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $rating ? '<i class="fas fa-star" style="color: #d4af37; font-size: 12px;"></i>' : '<i class="fas fa-star" style="color: rgba(212, 175, 55, 0.3); font-size: 12px;"></i>';
                                }
                                ?>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <span class="h5 fw-bold text-warning">₹<?php echo number_format($item['final_price'], 2); ?></span>
                                <?php if ($item['discount_percent'] > 0): ?>
                                <span class="text-muted text-decoration-line-through ms-2" style="font-size: 14px;">₹<?php echo number_format($item['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-footer border-0 bg-transparent">
                            <form method="POST" action="php/add_to_cart.php" class="d-grid">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn fw-bold" style="background: #d4af37; color: #0a0e27;">
                                    <i class="fas fa-shopping-bag"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
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
