<?php require_once 'php/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    
    $user_id = $_SESSION['user_id'];
    
    // Get user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    $error = '';
    $success = '';
    
    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        
        if (empty($fullname) || empty($phone)) {
            $error = 'Full name and phone are required';
        } else {
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, phone = ?, address = ?, city = ? WHERE user_id = ?");
            $stmt->bind_param("ssssi", $fullname, $phone, $address, $city, $user_id);
            
            if ($stmt->execute()) {
                $success = 'Profile updated successfully';
                $_SESSION['fullname'] = $fullname;
                // Refresh user data
                $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
            } else {
                $error = 'Error updating profile';
            }
            $stmt->close();
        }
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
                        <a class="nav-link active text-light" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <section style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                        <i class="fas fa-user-circle"></i> My Profile
                    </h1>

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <div class="card border-0" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 15px;">
                        <div class="card-body p-5">
                            <h5 class="card-title text-light fw-bold mb-4" style="border-bottom: 2px solid #d4af37; padding-bottom: 15px;">
                                Profile Information
                            </h5>

                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Full Name</label>
                                    <input type="text" class="form-control" name="fullname" value="<?php echo $user['fullname']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $user['email']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" value="<?php echo $user['phone']; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-light fw-bold">Address</label>
                                    <textarea class="form-control" name="address" rows="3" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;"><?php echo $user['address'] ?? ''; ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-light fw-bold">City</label>
                                    <input type="text" class="form-control" name="city" value="<?php echo $user['city'] ?? ''; ?>" style="background: rgba(255,255,255,0.1); border: 1px solid #d4af37; color: white;">
                                </div>

                                <button type="submit" class="btn fw-bold w-100" style="background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%); color: #0a0e27; border-radius: 8px; padding: 10px;">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </form>

                            <hr style="border-color: rgba(212, 175, 55, 0.2); margin: 30px 0;">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="orders.php" class="btn fw-bold w-100" style="background: #d4af37; color: #0a0e27; border-radius: 8px;">
                                        <i class="fas fa-box"></i> My Orders
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="wishlist.php" class="btn fw-bold w-100" style="border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 8px;">
                                        <i class="fas fa-heart"></i> Wishlist
                                    </a>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="php/logout.php" class="btn btn-danger fw-bold w-100">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
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
