<?php require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <?php
    
    // Get all users
    $result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = [];
    while ($user = $result->fetch_assoc()) {
        $users[] = $user;
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(10, 14, 39, 0.95); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-crown"></i> DUMBLEDORE ADMIN
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 admin-sidebar">
                <div class="nav flex-column p-3">
                    <a href="dashboard.php" class="admin-nav-link">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="products.php" class="admin-nav-link">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <a href="orders.php" class="admin-nav-link">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <a href="users.php" class="admin-nav-link active">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="logout.php" class="admin-nav-link text-danger mt-5">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10" style="background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 20, 45, 0.95) 100%); min-height: 100vh; padding: 40px;">
                <h1 class="mb-5" style="color: #d4af37; font-weight: 700;">
                    <i class="fas fa-users"></i> Manage Users
                </h1>

                <div class="card border-0 admin-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>City</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['fullname']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['phone']; ?></td>
                                        <td><?php echo $user['city'] ?? 'N/A'; ?></td>
                                        <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
