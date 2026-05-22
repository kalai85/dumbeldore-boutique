<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $user_id = $_SESSION['user_id'];
    
    // Get cart items
    $query = "SELECT c.*, p.product_name, p.price, p.discount_percent FROM cart c 
              JOIN products p ON c.product_id = p.product_id 
              WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cartResult = $stmt->get_result();
    
    $cartTotal = 0;
    $cartItems = [];
    
    while ($item = $cartResult->fetch_assoc()) {
        $item['final_price'] = $item['price'] - ($item['price'] * $item['discount_percent'] / 100);
        $item['item_total'] = $item['final_price'] * $item['quantity'];
        $cartTotal += $item['item_total'];
        $cartItems[] = $item;
    }
    $stmt->close();
    
    // Handle remove from cart
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_cart_id'])) {
        $cart_id = intval($_POST['remove_cart_id']);
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        header('Location: cart.php');
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
                        <a class="nav-link active text-light" href="cart.php">
                            <i class="fas fa-shopping-bag"></i> Cart
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                <i class="fas fa-shopping-bag"></i> Shopping Cart
            </h1>

            <?php if (empty($cartItems)): ?>
            <div class="alert alert-info text-center py-5" style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; color: #d4af37;">
                <i class="fas fa-inbox fa-2x mb-3"></i>
                <h4>Your cart is empty</h4>
                <p>Start shopping to add items to your cart</p>
                <a href="products.php" class="btn fw-bold mt-3" style="background: #d4af37; color: #0a0e27;">Continue Shopping</a>
            </div>
            <?php else: ?>

            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body">
                            <?php foreach ($cartItems as $item): ?>
                            <div class="border-bottom p-3" style="border-color: rgba(212, 175, 55, 0.2);">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="text-light fw-bold mb-2"><?php echo $item['product_name']; ?></h6>
                                        <p class="text-muted small mb-0">Price: <span style="color: #d4af37;">₹<?php echo number_format($item['final_price'], 2); ?></span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text" style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37;">Qty:</span>
                                            <input type="number" class="form-control" value="<?php echo $item['quantity']; ?>" style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; color: white;" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <h6 class="text-warning">₹<?php echo number_format($item['item_total'], 2); ?></h6>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="remove_cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px; position: sticky; top: 100px;">
                        <div class="card-body">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                Order Summary
                            </h5>

                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-light">Subtotal:</span>
                                <span class="text-light">₹<?php echo number_format($cartTotal, 2); ?></span>
                            </div>

                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-light">Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>

                            <div class="mb-4 d-flex justify-content-between fw-bold" style="border-top: 1px solid rgba(212, 175, 55, 0.2); padding-top: 15px;">
                                <span class="text-light">Total:</span>
                                <span style="color: #d4af37; font-size: 18px;">₹<?php echo number_format($cartTotal, 2); ?></span>
                            </div>

                            <a href="checkout.php" class="btn fw-bold w-100" style="background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); color: #0a0e27; border: none; border-radius: 8px; padding: 10px;">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </a>

                            <a href="products.php" class="btn w-100 mt-2 fw-bold" style="border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;">
                                <i class="fas fa-shopping-bag"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
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
