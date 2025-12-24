<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BikinEvent.my.id</title>

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
            min-height: 100vh;
            overflow-x: hidden;
            background: radial-gradient(ellipse at center, #6b46c1 0%, #4c1d95 50%, #1e1b4b 100%);
        }

        /* Container utama */
        .container {
            width: 100vw;
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Card register dengan glassmorphism effect */
        .register-card {
            width: 100%;
            max-width: 1000px;
            min-height: 750px;
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
            width: 45%;
            height: 100%;
            min-height: 750px;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
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
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.3) 0%, rgba(49, 46, 129, 0.2) 50%, rgba(76, 29, 149, 0.3) 100%);
            z-index: 2;
        }

        /* Hero image */
        .hero-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 30px 0 0 30px;
            filter: drop-shadow(0 0 20px rgba(168, 85, 247, 0.5));
            z-index: 1;
            display: block;
        }

        /* Overlay untuk gambar */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.3) 0%, rgba(49, 46, 129, 0.3) 50%, rgba(76, 29, 149, 0.3) 100%);
            z-index: 2;
            border-radius: 30px 0 0 30px;
        }

        /* Form register */
        .register-form {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            overflow-y: auto;
        }

        /* Title */
        .title {
            color: #f8fafc;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: left;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            margin-bottom: 30px;
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

        /* Form group */
        .form-group {
            margin-bottom: 25px;
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
            padding: 18px 0 8px 0;
            background: transparent;
            border: none;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            color: #f8fafc;
            font-size: 15px;
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
            top: 18px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 15px;
            font-weight: 500;
            pointer-events: none;
            transition: all 0.3s ease;
            transform-origin: left top;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control:focus+.floating-label,
        .form-control:not(:placeholder-shown)+.floating-label {
            transform: translateY(-18px) scale(0.8);
            color: #a855f7;
        }

        /* Register button */
        .register-button {
            width: 100%;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            margin: 20px 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .register-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .register-button:hover {
            transform: translateY(-2px);
            background: rgba(168, 85, 247, 0.3);
            box-shadow: 0 10px 25px rgba(168, 85, 247, 0.3);
            border-color: rgba(168, 85, 247, 0.5);
        }

        .register-button:hover::before {
            left: 100%;
        }

        .register-button:active {
            transform: translateY(0);
        }

        /* Footer links */
        .footer-links {
            text-align: center;
            margin-top: 15px;
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
        @media (max-width: 1200px) {
            .register-card {
                max-width: 900px;
            }

            .hero-section {
                width: 42%;
            }

            .register-form {
                padding: 40px 30px;
            }
        }

        @media (max-width: 1024px) {
            .container {
                padding: 15px;
            }

            .register-card {
                flex-direction: column;
                max-width: 600px;
                min-height: auto;
            }

            .hero-section {
                width: 100%;
                height: 280px;
                min-height: 280px;
                border-radius: 30px 30px 0 0;
            }

            .hero-image {
                border-radius: 30px 30px 0 0;
            }

            .register-form {
                padding: 40px 35px;
            }

            .title {
                font-size: 28px;
                text-align: center;
            }

            .subtitle {
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 12px;
            }

            .register-card {
                border-radius: 25px;
                max-width: 500px;
            }

            .hero-section {
                height: 220px;
                min-height: 220px;
                border-radius: 25px 25px 0 0;
            }

            .hero-image {
                border-radius: 25px 25px 0 0;
            }

            .register-form {
                padding: 35px 30px;
            }

            .form-group {
                margin-bottom: 22px;
            }

            .title {
                font-size: 26px;
            }

            .subtitle {
                font-size: 15px;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .register-card {
                border-radius: 20px;
                max-width: 450px;
            }

            .hero-section {
                height: 180px;
                min-height: 180px;
                border-radius: 20px 20px 0 0;
            }

            .hero-image {
                border-radius: 20px 20px 0 0;
            }

            .register-form {
                padding: 30px 25px;
            }

            .title {
                font-size: 24px;
            }

            .subtitle {
                font-size: 14px;
                margin-bottom: 25px;
            }

            .form-group {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 8px;
            }

            .register-card {
                border-radius: 18px;
                max-width: 400px;
            }

            .hero-section {
                height: 160px;
                min-height: 160px;
                border-radius: 18px 18px 0 0;
            }

            .hero-image {
                border-radius: 18px 18px 0 0;
            }

            .register-form {
                padding: 25px 20px;
            }

            .title {
                font-size: 22px;
            }

            .subtitle {
                font-size: 13px;
                margin-bottom: 25px;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-control {
                font-size: 14px;
                padding: 16px 0 6px 0;
            }

            .floating-label {
                font-size: 14px;
                top: 16px;
            }

            .register-button {
                height: 48px;
                font-size: 15px;
            }
        }

        @media (max-width: 360px) {
            .container {
                padding: 6px;
            }

            .register-card {
                border-radius: 15px;
                max-width: 350px;
            }

            .hero-section {
                height: 140px;
                min-height: 140px;
                border-radius: 15px 15px 0 0;
            }

            .hero-image {
                border-radius: 15px 15px 0 0;
            }

            .register-form {
                padding: 20px 18px;
            }

            .title {
                font-size: 20px;
            }

            .subtitle {
                font-size: 12px;
            }

            .form-control {
                font-size: 13px;
            }

            .floating-label {
                font-size: 13px;
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

        <div class="register-card">
            <div class="hero-section">
                <img class="hero-image"
                    src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&h=900&q=80"
                    alt="Beautiful Mountain Landscape"
                    onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%)'" />
                <!-- Fallback content jika gambar tidak load -->
                <div style="position: absolute; z-index: 10; text-align: center; color: white; opacity: 0.9; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="fas fa-feather-alt" style="font-size: 80px; margin-bottom: 20px; color: #a855f7; filter: drop-shadow(0 0 10px rgba(168, 85, 247, 0.5));"></i>
                    <h3 style="font-size: 24px; font-weight: 600; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Welcome</h3>
                    <p style="font-size: 16px; margin: 10px 0 0 0; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Create your account</p>
                </div>
            </div>

            <div class="register-form">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= base_url('assets/images/bikinevent-icon.svg') ?>" alt="BikinEvent Icon" style="width: 36px; height: 36px; margin-right: 10px;">
                    <h1 class="title mb-0">Daftar di BikinEvent.my.id</h1>
                </div>
                <p class="subtitle">Bergabunglah dan mulai buat event impianmu</p>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul style="margin: 5px 0 0 20px; padding: 0;">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/register" method="post">
                    <div class="form-group">
                        <div class="input-container">
                            <input type="email" name="email" class="form-control" id="email" placeholder=" " required>
                            <label class="floating-label" for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-container">
                            <input type="text" name="name" class="form-control" id="name" placeholder=" " required>
                            <label class="floating-label" for="name">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-container">
                            <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder=" " required>
                            <label class="floating-label" for="phone_number">
                                <i class="fas fa-phone"></i> Nomor Telepon
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-container">
                            <input type="password" name="password" class="form-control" id="password" placeholder=" " required>
                            <label class="floating-label" for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-container">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder=" " required>
                            <label class="floating-label" for="confirm_password">
                                <i class="fas fa-lock"></i> Konfirmasi Password
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="register-button">
                        <i class="fas fa-user-plus"></i> Register
                    </button>

                    <div class="footer-links">
                        <p>Already have an account? <a href="/login" class="link">Login Now</a></p>
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