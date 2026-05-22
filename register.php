<?php 
require_once 'php/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMBLEDORE BOUTIQUE - Premium Register</title>
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
            padding: 20px 0;
        }

        /* Particle Animation Background */
        .particle-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .particle {
            position: absolute;
            pointer-events: none;
        }

        /* Content Wrapper */
        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        /* Premium Auth Card with Glassmorphism */
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

        /* Logo Styling */
        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo h1 {
            font-size: 32px;
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
            margin-top: 8px;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            color: #d4af37;
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            padding: 10px 14px;
            color: #ffffff;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #d4af37;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
            color: #ffffff;
        }

        /* Submit Button */
        .btn-primary-custom {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            border: none;
            border-radius: 8px;
            color: #0a0e27;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            margin-top: 15px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
            background: linear-gradient(135deg, #f0e68c 0%, #d4af37 100%);
        }

        /* Toggle Link */
        .auth-toggle {
            text-align: center;
            margin-top: 20px;
            color: #b0b0b0;
            font-size: 13px;
        }

        .auth-toggle a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-toggle a:hover {
            color: #f0e68c;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
        }

        /* Error and Success Messages */
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            border: none;
            animation: slideDown 0.5s ease-out;
            font-size: 13px;
            padding: 10px 15px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #51cf66;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        /* Animations */
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

        /* Responsive */
        @media (max-width: 576px) {
            .auth-card {
                padding: 30px 20px;
            }

            .brand-logo h1 {
                font-size: 24px;
            }

            .form-control {
                font-size: 12px;
                padding: 9px 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Particle Background -->
    <div class="particle-bg" id="particleBg"></div>

    <!-- Auth Container -->
    <div class="auth-container">
        <!-- Auth Card -->
        <div class="auth-card">
            <!-- Brand Logo -->
            <div class="brand-logo">
                <h1><i class="fas fa-wand-magic-sparkles"></i> DUMBLEDORE</h1>
                <p>Luxury Boutique</p>
            </div>

            <?php
            $error = '';

            // Check if form is submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $fullname = trim($_POST['fullname'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';

                // Validation
                if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
                    $error = 'All fields are required';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email format';
                } else if (strlen($phone) < 10) {
                    $error = 'Invalid phone number';
                } else if (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters';
                } else if ($password !== $confirm_password) {
                    $error = 'Passwords do not match';
                } else {
                    // Check if email already exists
                    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $error = 'Email already registered. Please login';
                    } else {
                        // Hash password
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                        // Insert user
                        $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, password) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $fullname, $email, $phone, $hashed_password);

                        if ($stmt->execute()) {
                            // Registration successful - redirect to login
                            echo "<script>window.location.href='login.php?registered=1';</script>";
                            exit();
                        } else {
                            $error = 'Registration failed. Please try again';
                        }
                    }
                    $stmt->close();
                }
            }
            ?>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>

                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>

            <!-- Toggle to Login -->
            <div class="auth-toggle">
                Already have an account? <a href="login.php">Login Here</a>
            </div>
        </div>
    </div>

    <!-- Floating Stars Script -->
    <script>
        // Create particle effect
        function createParticles() {
            const bg = document.getElementById('particleBg');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.width = Math.random() * 3 + 1 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.background = Math.random() > 0.5 ? '#d4af37' : '#f0e68c';
                particle.style.borderRadius = '50%';
                particle.style.opacity = Math.random() * 0.5 + 0.2;
                particle.style.animation = `float ${5 + Math.random() * 10}s infinite`;

                bg.appendChild(particle);
            }
        }

        createParticles();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
