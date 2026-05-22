<?php require_once '../php/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DUMBLEDORE BOUTIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1a3a 50%, #0f0f1e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .particle-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            animation: slideInUp 0.8s ease-out;
            position: relative;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 20%;
            width: 60%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #d4af37, transparent);
            border-radius: 50%;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-logo h1 {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
            letter-spacing: 2px;
            animation: glowPulse 3s ease-in-out infinite;
        }

        .brand-logo p {
            color: #b0b0b0;
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            color: #d4af37;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ffffff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #d4af37;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
            color: #ffffff;
        }

        .btn-primary-custom {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            border: none;
            border-radius: 10px;
            color: #0a0e27;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 25px;
            border: none;
            animation: slideDown 0.5s ease-out;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glowPulse {
            0%, 100% {
                text-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
            }
            50% {
                text-shadow: 0 0 40px rgba(212, 175, 55, 0.6);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .badge-admin {
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            color: #0a0e27;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="particle-bg" id="particleBg"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="brand-logo">
                <div class="badge-admin">
                    <i class="fas fa-lock"></i> ADMIN PANEL
                </div>
                <h1><i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE</h1>
                <p>Luxury Boutique</p>
            </div>

            <?php
            $error = '';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (empty($email) || empty($password)) {
                    $error = 'Please enter both email and password';
                } else {
                    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password, role, status FROM admin WHERE admin_email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $admin = $result->fetch_assoc();

                        if ($admin['status'] != 'active') {
                            $error = 'Admin account is inactive';
                        } else if (password_verify($password, $admin['admin_password'])) {
                            $_SESSION['admin_id'] = $admin['admin_id'];
                            $_SESSION['admin_name'] = $admin['admin_name'];
                            $_SESSION['admin_email'] = $admin['admin_email'];
                            $_SESSION['admin_role'] = $admin['role'];
                            $_SESSION['user_type'] = 'admin';

                            header('Location: dashboard.php');
                            exit();
                        } else {
                            $error = 'Invalid password';
                        }
                    } else {
                        $error = 'Admin not found';
                    }
                    $stmt->close();
                }
            }
            ?>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter admin email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-sign-in-alt"></i> Admin Login
                </button>
            </form>

            <div style="text-align: center; margin-top: 25px; color: #b0b0b0; font-size: 14px;">
                <a href="../login.php" class="text-decoration-none" style="color: #d4af37;">Back to Customer Login</a>
            </div>
        </div>
    </div>

    <script>
        function createParticles() {
            const bg = document.getElementById('particleBg');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'absolute';
                particle.style.width = Math.random() * 3 + 1 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.background = Math.random() > 0.5 ? '#d4af37' : '#f0e68c';
                particle.style.borderRadius = '50%';
                particle.style.opacity = Math.random() * 0.5 + 0.2;
                particle.style.animation = `float ${5 + Math.random() * 10}s infinite`;
                particle.style.pointerEvents = 'none';

                bg.appendChild(particle);
            }
        }

        createParticles();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
