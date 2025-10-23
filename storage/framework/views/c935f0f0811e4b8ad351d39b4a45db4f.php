<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Sign Up - Latino Laundry</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --primary-light: #dbeafe;
            --success-color: #10b981;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
            --border-hover: #d1d5db;
            --background: #ffffff;
            --surface: #f9fafb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Mesh Gradient Background (Same as Login) */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(at 27% 37%, hsla(215, 98%, 61%, 0.15) 0px, transparent 50%),
                radial-gradient(at 97% 21%, hsla(125, 98%, 72%, 0.12) 0px, transparent 50%),
                radial-gradient(at 52% 99%, hsla(354, 98%, 61%, 0.12) 0px, transparent 50%),
                radial-gradient(at 10% 29%, hsla(256, 96%, 67%, 0.15) 0px, transparent 50%),
                radial-gradient(at 97% 96%, hsla(38, 60%, 74%, 0.12) 0px, transparent 50%),
                radial-gradient(at 33% 50%, hsla(222, 67%, 73%, 0.15) 0px, transparent 50%),
                radial-gradient(at 79% 53%, hsla(343, 68%, 79%, 0.12) 0px, transparent 50%);
            background-color: #f8fafc;
            z-index: -2;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
            z-index: -1;
        }

        .register-container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
            margin: auto;
        }

        .register-card {
            background: var(--background);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 40px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        /* Logo Section */
        .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo img {
            max-width: 140px;
            height: auto;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background-color: #fee2e2;
            color: var(--error-color);
            border: 1px solid #fecaca;
        }

        /* Form Styles */
        .register-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Floating Input Group */
        .floating-input-group {
            position: relative;
            width: 100%;
        }

        .floating-input {
            width: 100%;
            height: 52px;
            padding: 18px 16px 6px 16px;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            background-color: var(--background);
            color: var(--text-primary);
            font-size: 0.9375rem;
            font-family: inherit;
            font-weight: 400;
            line-height: 1.5;
            transition: var(--transition);
            outline: none;
        }

        .floating-input:hover {
            border-color: var(--border-hover);
        }

        .floating-input:focus {
            border-color: var(--primary-color);
            border-width: 2px;
            padding: 18px 15px 6px 15px;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        /* Floating Label */
        .floating-label {
            position: absolute;
            left: 16px;
            top: 16px;
            color: var(--text-muted);
            font-size: 0.9375rem;
            font-weight: 400;
            pointer-events: none;
            transition: var(--transition);
            background-color: var(--background);
            padding: 0 4px;
            z-index: 1;
        }

        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label,
        .floating-input.has-value + .floating-label {
            top: -8px;
            left: 12px;
            font-size: 0.75rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Error States */
        .floating-input-group.error .floating-input {
            border-color: var(--error-color);
        }

        .floating-input-group.error .floating-input:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .floating-input-group.error .floating-label {
            color: var(--error-color);
        }

        .error-text {
            display: block;
            color: var(--error-color);
            font-size: 0.8125rem;
            margin-top: 6px;
            margin-left: 4px;
            font-weight: 500;
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 8px;
            margin-bottom: -8px;
        }

        .strength-bar {
            width: 100%;
            height: 4px;
            background: var(--surface);
            border-radius: 4px;
            margin-bottom: 6px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease, background-color 0.3s ease;
            width: 0%;
        }

        .strength-text {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .strength-weak .strength-fill { width: 25%; background-color: var(--error-color); }
        .strength-fair .strength-fill { width: 50%; background-color: var(--warning-color); }
        .strength-good .strength-fill { width: 75%; background-color: #3b82f6; }
        .strength-strong .strength-fill { width: 100%; background-color: var(--success-color); }

        .strength-weak .strength-text { color: var(--error-color); }
        .strength-fair .strength-text { color: var(--warning-color); }
        .strength-good .strength-text { color: #3b82f6; }
        .strength-strong .strength-text { color: var(--success-color); }

        /* Password Match Indicator */
        .password-match-container {
            margin-top: 8px;
            margin-bottom: -8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .match-icon {
            width: 16px;
            height: 16px;
            display: none;
        }

        .password-match-container.match .check-icon { display: block; color: var(--success-color); }
        .password-match-container.mismatch .x-icon { display: block; color: var(--error-color); }

        .password-match-text {
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .password-match-text.match { color: var(--success-color); }
        .password-match-text.mismatch { color: var(--error-color); }

        /* Terms Section */
        .terms-section {
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .terms-checkbox-input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .terms-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
            cursor: pointer;
        }

        .terms-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }

        .terms-link:hover {
            text-decoration: underline;
            opacity: 0.8;
        }

        /* Register Button */
        .register-button {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: var(--shadow-md);
            margin-top: 8px;
        }

        .register-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.4);
        }

        .register-button:active:not(:disabled) {
            transform: translateY(0);
        }

        .register-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .button-loader {
            display: none;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .login-link p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .login-text {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s ease;
        }

        .login-text:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Copyright */
        .copyright {
            text-align: center;
            margin-top: 24px;
        }

        .copyright p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.8125rem;
            font-weight: 400;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            body {
                padding: 20px 16px;
            }

            .register-card {
                padding: 32px 24px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .logo img {
                max-width: 120px;
            }

            .floating-input {
                font-size: 16px; /* Prevent iOS zoom */
            }
        }

        @media (max-height: 700px) {
            body {
                padding: 20px;
            }

            .register-card {
                padding: 28px 32px;
            }

            .logo {
                margin-bottom: 20px;
            }

            .header {
                margin-bottom: 24px;
            }

            .register-form {
                gap: 16px;
            }
        }

        /* Autofill handling */
        .floating-input:-webkit-autofill + .floating-label {
            top: -8px;
            left: 12px;
            font-size: 0.75rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Placeholder handling */
        .floating-input::placeholder {
            opacity: 0;
        }

        .floating-input:focus::placeholder {
            opacity: 0.4;
            color: var(--text-muted);
        }

        /* Toast Notification Styles */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 0;
            z-index: 1000;
            min-width: 320px;
            max-width: 400px;
            overflow: hidden;
            transform: translateX(100%);
            animation: slideInRight 0.5s ease-out forwards;
        }

        .toast-content {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            gap: 12px;
        }

        .toast-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-notification.success .toast-icon {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .toast-message h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .toast-message p {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.4;
        }

        .toast-progress {
            height: 4px;
            background: #f3f4f6;
            overflow: hidden;
        }

        .toast-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            width: 100%;
            animation: progressBar 4s linear forwards;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        .toast-notification.hide {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        /* Smooth entrance animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Logo -->
            <div class="logo">
                <img src="<?php echo e(asset('images/logo-removebg-preview.png')); ?>" alt="Latino Laundry">
            </div>

            <!-- Header -->
            <div class="header">
                <h1>Create Account</h1>
                <p>Join Latino Laundry today</p>
            </div>

            <!-- Session Status -->
            <?php if(session('status')): ?>
                <div class="alert alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <!-- Success Toast Notification -->
            <?php if(session('success')): ?>
                <div class="toast-notification success" id="successToast">
                    <div class="toast-content">
                        <div class="toast-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <div class="toast-message">
                            <h4>Account Created Successfully!</h4>
                            <p><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                    <div class="toast-progress">
                        <div class="toast-progress-bar"></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm" class="register-form">
                <?php echo csrf_field(); ?>

                <!-- Name Field -->
                <div class="floating-input-group <?php echo e($errors->has('name') ? 'error' : ''); ?>">
                    <input 
                        type="text"
                        id="name"
                        name="name"
                        value="<?php echo e(old('name')); ?>"
                        placeholder=" "
                        required
                        autocomplete="name"
                        class="floating-input <?php echo e(old('name') ? 'has-value' : ''); ?>"
                    >
                    <label for="name" class="floating-label">Full Name</label>
                    <?php if($errors->has('name')): ?>
                        <span class="error-text"><?php echo e($errors->first('name')); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Email Field -->
                <div class="floating-input-group <?php echo e($errors->has('email') ? 'error' : ''); ?>">
                    <input 
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        placeholder=" "
                        required
                        autocomplete="email"
                        class="floating-input <?php echo e(old('email') ? 'has-value' : ''); ?>"
                    >
                    <label for="email" class="floating-label">Email Address</label>
                    <?php if($errors->has('email')): ?>
                        <span class="error-text"><?php echo e($errors->first('email')); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Phone Number Field -->
                <div class="floating-input-group <?php echo e($errors->has('phone_number') ? 'error' : ''); ?>">
                    <input 
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="<?php echo e(old('phone_number')); ?>"
                        placeholder=" "
                        required
                        autocomplete="tel"
                        class="floating-input <?php echo e(old('phone_number') ? 'has-value' : ''); ?>"
                    >
                    <label for="phone_number" class="floating-label">Phone Number</label>
                    <?php if($errors->has('phone_number')): ?>
                        <span class="error-text"><?php echo e($errors->first('phone_number')); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password Field -->
                <div class="floating-input-group <?php echo e($errors->has('password') ? 'error' : ''); ?>">
                    <input 
                        type="password"
                        id="password"
                        name="password"
                        placeholder=" "
                        required
                        autocomplete="new-password"
                        class="floating-input"
                    >
                    <label for="password" class="floating-label">Password</label>
                    <?php if($errors->has('password')): ?>
                        <span class="error-text"><?php echo e($errors->first('password')); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password Strength Indicator -->
                <div class="password-strength" id="passwordStrength" style="display: none;">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-text" id="strengthText"></span>
                </div>

                <!-- Confirm Password Field -->
                <div class="floating-input-group <?php echo e($errors->has('password_confirmation') ? 'error' : ''); ?>">
                    <input 
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder=" "
                        required
                        autocomplete="new-password"
                        class="floating-input"
                    >
                    <label for="password_confirmation" class="floating-label">Confirm Password</label>
                    <?php if($errors->has('password_confirmation')): ?>
                        <span class="error-text"><?php echo e($errors->first('password_confirmation')); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password Match Indicator -->
                <div class="password-match-container" id="passwordMatchContainer" style="display: none;">
                    <svg class="match-icon check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg class="match-icon x-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="password-match-text" id="passwordMatchText"></span>
                </div>

                <!-- Terms & Conditions -->
                <div class="terms-section">
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required class="terms-checkbox-input">
                        <label for="terms" class="terms-label">
                            I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="register-button" id="registerBtn">
                    <span class="button-text">Create Account</span>
                    <div class="button-loader">
                        <div class="spinner"></div>
                    </div>
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p>Already have an account? <a href="<?php echo e(route('login')); ?>" class="login-text">Sign in</a></p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            <p>© 2025 Latino Laundry. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Form submission handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                return false;
            }

            const btn = document.getElementById('registerBtn');
            const buttonText = btn.querySelector('.button-text');
            const buttonLoader = btn.querySelector('.button-loader');

            btn.disabled = true;
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'flex';
        });

        // Password strength calculator
        function calculatePasswordStrength(password) {
            let score = 0;

            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            if (score <= 2) return { class: 'strength-weak', text: 'Weak password' };
            if (score === 3) return { class: 'strength-fair', text: 'Fair password' };
            if (score === 4) return { class: 'strength-good', text: 'Good password' };
            return { class: 'strength-strong', text: 'Strong password' };
        }

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        // Debug: Check if elements exist
        console.log('Password input:', passwordInput);
        console.log('Password strength:', passwordStrength);
        console.log('Strength fill:', strengthFill);
        console.log('Strength text:', strengthText);

        if (passwordInput && passwordStrength && strengthFill && strengthText) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = calculatePasswordStrength(password);

                console.log('Password:', password, 'Strength:', strength);

                // Remove all strength classes from the password strength container
                passwordStrength.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');

                if (password.length === 0) {
                    passwordStrength.style.display = 'none';
                    return;
                }

                // Show the strength indicator and add the appropriate class
                passwordStrength.style.display = 'block';
                passwordStrength.classList.add(strength.class);
                strengthText.textContent = strength.text;
            });
        } else {
            console.error('Password strength elements not found!');
        }

        // Password match validation
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordMatchContainer = document.getElementById('passwordMatchContainer');
        const passwordMatchText = document.getElementById('passwordMatchText');

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            passwordMatchContainer.classList.remove('match', 'mismatch');

            if (confirmPassword.length === 0) {
                passwordMatchContainer.style.display = 'none';
                return;
            }

            passwordMatchContainer.style.display = 'flex';

            if (password === confirmPassword) {
                passwordMatchContainer.classList.add('match');
                passwordMatchText.textContent = 'Passwords match';
            } else {
                passwordMatchContainer.classList.add('mismatch');
                passwordMatchText.textContent = 'Passwords do not match';
            }
        }

        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        passwordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value.length > 0) {
                checkPasswordMatch();
            }
        });

        // Toast notification handler
        document.addEventListener('DOMContentLoaded', function() {
            const successToast = document.getElementById('successToast');
            
            if (successToast) {
                // Show toast for 4 seconds, then hide and redirect
                setTimeout(() => {
                    successToast.classList.add('hide');
                    
                    // Redirect to login page after animation completes
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route("login")); ?>';
                    }, 300); // Wait for slideOut animation to complete
                }, 4000); // Show for 4 seconds
            }

            // Floating label handler
            const inputs = document.querySelectorAll('.floating-input');

            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    input.classList.add('has-value');
                }

                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/auth/register.blade.php ENDPATH**/ ?>