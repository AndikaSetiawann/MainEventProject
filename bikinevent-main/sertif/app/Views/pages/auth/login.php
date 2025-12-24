<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BikinEvent.my.id</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/images/bikinevent-favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/bikinevent-favicon.svg') ?>">
    <link rel="mask-icon" href="<?= base_url('assets/images/bikinevent-favicon.svg') ?>" color="#667eea">
    <meta name="theme-color" content="#667eea">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset dan base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background: radial-gradient(ellipse at center, #6b46c1 0%, #4c1d95 50%, #1e1b4b 100%);
        }

        /* Container utama */
        .container {
            width: 100vw;
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Card login dengan glassmorphism effect */
        .login-card {
            width: 900px;
            height: 500px;
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            display: flex;
            overflow: hidden;
        }

        /* Hero section dengan gambar */
        .hero-section {
            width: 400px;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.8) 0%, rgba(49, 46, 129, 0.8) 50%, rgba(76, 29, 149, 0.8) 100%);
            border-radius: 30px 0 0 30px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.4) 0%, rgba(49, 46, 129, 0.3) 50%, rgba(76, 29, 149, 0.4) 100%);
            z-index: 2;
        }

        /* Hero image */
        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 30px 0 0 30px;
            filter: drop-shadow(0 0 20px rgba(168, 85, 247, 0.5));
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* Form login */
        .login-form {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Title */
        .title {
            color: #f8fafc;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: left;
        }

        /* Alert messages */
        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert-warning {
            color: #f59e0b;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .alert-success {
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.3);
        }

        /* Form group */
        .form-group {
            margin-bottom: 30px;
            position: relative;
        }

        /* Input container */
        .input-container {
            position: relative;
            width: 100%;
        }

        /* Input field */
        .form-control {
            width: 100%;
            padding: 20px 0 8px 0;
            background: transparent;
            border: none;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            color: #f8fafc;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-bottom-color: #a855f7;
        }

        /* Floating label */
        .floating-label {
            position: absolute;
            left: 0;
            top: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
            font-weight: 500;
            pointer-events: none;
            transition: all 0.3s ease;
            transform-origin: left top;
        }

        .form-control:focus+.floating-label,
        .form-control:not(:placeholder-shown)+.floating-label {
            transform: translateY(-20px) scale(0.8);
            color: #a855f7;
        }

        /* Login button */
        .login-button {
            width: 100%;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 18px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            background: rgba(168, 85, 247, 0.3);
            box-shadow: 0 10px 25px rgba(168, 85, 247, 0.3);
            border-color: rgba(168, 85, 247, 0.5);
        }

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Footer links */
        .footer-links {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .link {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .link:hover {
            color: #a855f7;
            text-decoration: underline;
        }

        /* Glowing particles effect */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #a855f7;
            border-radius: 50%;
            animation: float 6s infinite linear;
            opacity: 0.6;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.6;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .login-card {
                width: 95vw;
                height: auto;
                flex-direction: column;
                max-width: 500px;
            }

            .hero-section {
                width: 100%;
                height: 200px;
                border-radius: 30px 30px 0 0;
            }

            .hero-image {
                width: 100%;
                height: 100%;
                border-radius: 30px 30px 0 0;
            }

            .login-form {
                padding: 40px 30px;
            }

            .title {
                font-size: 28px;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 150px;
            }

            .hero-image {
                width: 100%;
                height: 100%;
                border-radius: 30px 30px 0 0;
            }

            .login-form {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Floating particles -->
        <div class="particles">
            <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
            <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
            <div class="particle" style="left: 50%; animation-delay: 4s;"></div>
            <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
            <div class="particle" style="left: 70%; animation-delay: 0.5s;"></div>
            <div class="particle" style="left: 80%; animation-delay: 1.5s;"></div>
            <div class="particle" style="left: 90%; animation-delay: 2.5s;"></div>
        </div>

        <div class="login-card">
            <div class="hero-section">
                <img class="hero-image" src="<?= base_url('images/punix.jpeg') ?>" alt="Hero Image" />
            </div>

            <div class="login-form">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <img src="<?= base_url('assets/images/bikinevent-icon.svg') ?>" alt="BikinEvent Icon" style="width: 40px; height: 40px; margin-right: 10px;">
                    <h1 class="title mb-0">Login ke BikinEvent.my.id</h1>
                </div>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="/auth/attemptLogin" method="post">
                    <div class="form-group">
                        <div class="input-container">
                            <input type="email" name="email" class="form-control" id="email" placeholder=" " required>
                            <label class="floating-label" for="email">Email</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-container">
                            <input type="password" name="password" class="form-control" id="password" placeholder=" " required>
                            <label class="floating-label" for="password">Password</label>
                        </div>
                    </div>

                    <button type="submit" class="login-button">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <div class="footer-links">
                        <a href="/register" class="link">Create an account</a>
                        <a href="#" class="link">Forgot password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Enhanced input focus effect
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentElement.classList.remove('focused');
                }
            });

            // Check if input has value on page load
            if (input.value !== '') {
                input.parentElement.classList.add('focused');
            }
        });

        // Add smooth scroll effect to particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 2 + 's';
            particle.style.animationDuration = (Math.random() * 3 + 4) + 's';

            document.querySelector('.particles').appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 7000);
        }

        // Create particles periodically
        setInterval(createParticle, 800);
    </script>
</body>

</html>