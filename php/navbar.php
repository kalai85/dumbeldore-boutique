<?php
/**
 * Navbar Component
 * Reusable navbar for all pages
 * 
 * Usage: require_once 'php/navbar.php';
 */

if (!function_exists('is_logged_in')) {
    require_once __DIR__ . '/config_enhanced.php';
}

$page_name = basename($_SERVER['PHP_SELF'], '.php');
$cart_count = is_logged_in() ? get_cart_count(get_current_user_id()) : 0;
$wishlist_count = is_logged_in() ? get_wishlist_count(get_current_user_id()) : 0;
$user_name = is_logged_in() ? ($_SESSION['fullname'] ?? 'User') : '';
?>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
    <div class="container-fluid px-4">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="<?php echo base_url('dashboard.php'); ?>" style="font-size: 24px; background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
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
                    <a class="nav-link text-light fw-500 <?php echo $page_name === 'dashboard' ? 'active' : ''; ?>" href="<?php echo base_url('dashboard.php'); ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>

                <?php if (is_logged_in()): ?>
                <li class="nav-item">
                    <a class="nav-link text-light fw-500 <?php echo $page_name === 'products' ? 'active' : ''; ?>" href="<?php echo base_url('products.php'); ?>">
                        <i class="fas fa-shopping-bags"></i> Shop
                    </a>
                </li>

                <!-- Search Bar -->
                <li class="nav-item w-100 w-lg-auto mt-2 mt-lg-0">
                    <form class="d-flex" action="<?php echo base_url('search.php'); ?>" method="GET">
                        <input class="form-control form-control-sm" type="search" name="q" placeholder="Search..." style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white; border-radius: 5px 0 0 5px;">
                        <button class="btn btn-sm" type="submit" style="background: #d4af37; color: #0a0e27; border-radius: 0 5px 5px 0; border: 1px solid #d4af37;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </li>

                <!-- Wishlist -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?php echo base_url('wishlist.php'); ?>" title="Wishlist">
                        <i class="fas fa-heart fa-lg" style="color: #d4af37;"></i>
                        <?php if ($wishlist_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $wishlist_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?php echo base_url('cart.php'); ?>" title="Shopping Cart">
                        <i class="fas fa-shopping-bag fa-lg" style="color: #d4af37;"></i>
                        <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #d4af37; color: #0a0e27; font-size: 10px;">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light fw-500" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg" style="color: #d4af37;"></i>
                        <?php echo h(substr($user_name, 0, 8)); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" style="background: rgba(10, 14, 39, 0.95); border-color: #d4af37;">
                        <li><a class="dropdown-item" href="<?php echo base_url('profile.php'); ?>"><i class="fas fa-user"></i> My Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('orders.php'); ?>"><i class="fas fa-box"></i> My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo base_url('php/logout.php'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link text-light fw-500" href="<?php echo base_url('login.php'); ?>">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-500" href="<?php echo base_url('register.php'); ?>" style="background: #d4af37; color: #0a0e27; padding: 5px 15px; border-radius: 5px;">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
