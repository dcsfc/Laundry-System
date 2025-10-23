<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Latino Laundry</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-color: #1976d2;
            --error-color: #d32f2f;
            --text-primary: #212121;
            --text-secondary: #757575;
            --border-color: #e0e0e0;
            --border-hover: #9e9e9e;
            --background: #ffffff;
            --surface: #f5f5f5;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* UNIQUE BACKGROUND OPTIONS - UNCOMMENT THE ONE YOU WANT */

        /* Option 1: Mesh Gradient (Modern SaaS style - CURRENTLY ACTIVE) */
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

        /* Option 2: Animated Gradient Orbs (Uncomment to use)
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3), transparent 25%),
                radial-gradient(circle at 80% 80%, rgba(138, 43, 226, 0.25), transparent 25%),
                radial-gradient(circle at 40% 20%, rgba(72, 149, 239, 0.3), transparent 25%),
                radial-gradient(circle at 90% 10%, rgba(255, 107, 107, 0.2), transparent 25%);
            background-color: #0f172a;
            animation: gradientOrbs 20s ease infinite;
            z-index: -2;
        }

        @keyframes gradientOrbs {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(5%, -5%) rotate(120deg); }
            66% { transform: translate(-5%, 5%) rotate(240deg); }
        }
        */

        /* Option 3: Geometric Pattern Background (Uncomment to use)
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #1a1d29;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100px 100px, 100px 100px, 20px 20px, 20px 20px;
            background-position: -1px -1px, -1px -1px, -1px -1px, -1px -1px;
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
        */

        /* Option 4: Diagonal Stripes with Blur (Uncomment to use)
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: diagonalFlow 15s ease infinite;
            z-index: -2;
            opacity: 0.8;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(100px);
            z-index: -1;
        }

        @keyframes diagonalFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        */

        /* Option 5: Glassmorphism with Colored Blobs (Uncomment to use)
        body {
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
        }

        body::before {
            content: '';
            position: fixed;
            top: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255, 107, 107, 0.3);
            border-radius: 50%;
            filter: blur(80px);
            animation: floatBlob1 20s ease-in-out infinite;
            z-index: -1;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: rgba(72, 149, 239, 0.3);
            border-radius: 50%;
            filter: blur(80px);
            animation: floatBlob2 25s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes floatBlob1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(100px, 100px) scale(1.1); }
        }

        @keyframes floatBlob2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-100px, -50px) scale(1.2); }
        }
        */

        /* Option 6: Navy Professional with Accents (Uncomment to use)
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 50%, #1e293b 100%);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(at 40% 20%, rgba(59, 130, 246, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(139, 92, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 80% 100%, rgba(59, 130, 246, 0.1) 0px, transparent 50%);
            z-index: -1;
        }
        */

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            padding: 48px 40px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo img {
            max-width: 180px;
            height: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 400;
        }

        /* Alert Styles */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #ffebee;
            color: var(--error-color);
            border: 1px solid #ffcdd2;
        }

        .alert-success {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        /* Floating Input Group */
        .floating-input-group {
            position: relative;
            margin-bottom: 24px;
            width: 100%;
        }

        /* Input Field Styles */
        .floating-input {
            width: 100%;
            height: 56px;
            padding: 20px 16px 8px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: transparent;
            color: var(--text-primary);
            font-size: 16px;
            font-family: inherit;
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
            padding: 20px 15px 8px 15px;
        }

        /* Floating Label */
        .floating-label {
            position: absolute;
            left: 16px;
            top: 18px;
            color: var(--text-secondary);
            font-size: 16px;
            font-weight: 400;
            pointer-events: none;
            transition: var(--transition);
            background-color: var(--background);
            padding: 0 4px;
            z-index: 1;
        }

        /* Label animation states */
        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label,
        .floating-input.has-value + .floating-label {
            top: -8px;
            left: 12px;
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Error States */
        .floating-input-group.error .floating-input {
            border-color: var(--error-color);
        }

        .floating-input-group.error .floating-label {
            color: var(--error-color);
        }

        .floating-input-group.error .floating-input:focus + .floating-label,
        .floating-input-group.error .floating-input:not(:placeholder-shown) + .floating-label {
            color: var(--error-color);
        }

        /* Error Text */
        .error-text {
            display: block;
            color: var(--error-color);
            font-size: 12px;
            margin-top: 6px;
            margin-left: 4px;
            font-weight: 400;
        }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .remember-label {
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            font-size: 14px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }

        .forgot-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Login Button */
        .login-button {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .button-loader {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Sign Up Link */
        .signup-link {
            text-align: center;
        }

        .signup-link p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .signup-text {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s ease;
        }

        .signup-text:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Copyright */
        .copyright {
            text-align: center;
            margin-top: 24px;
        }

        .copyright p {
            color: rgba(0, 0, 0, 0.5);
            font-size: 13px;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }

            .logo img {
                max-width: 140px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .floating-input {
                font-size: 16px; /* Prevent zoom on iOS */
            }
        }

        /* Autofill handling */
        .floating-input:-webkit-autofill + .floating-label {
            top: -8px;
            left: 12px;
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Placeholder handling */
        .floating-input::placeholder {
            opacity: 0;
        }

        .floating-input:focus::placeholder {
            opacity: 0.5;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo">
                <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Latino Laundry">
            </div>

            <!-- Header -->
            <div class="header">
                <p>Sign in to your Latino Laundry account</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-error">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('customer.login') }}" id="loginForm" class="login-form">
                @csrf

                <!-- Email Field -->
                <div class="floating-input-group {{ $errors->has('email') ? 'error' : '' }}">
                    <input 
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder=" "
                        required
                        class="floating-input {{ old('email') ? 'has-value' : '' }}"
                    >
                    <label for="email" class="floating-label">Email</label>
                    @if($errors->has('email'))
                        <span class="error-text">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <!-- Password Field -->
                <div class="floating-input-group {{ $errors->has('password') ? 'error' : '' }}">
                    <input 
                        type="password"
                        id="password"
                        name="password"
                        placeholder=" "
                        required
                        class="floating-input"
                    >
                    <label for="password" class="floating-label">Password</label>
                    @if($errors->has('password'))
                        <span class="error-text">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                        <label for="remember" class="remember-label">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-button" id="loginBtn">
                    <span class="button-text">Sign In</span>
                    <div class="button-loader" style="display: none;">
                        <div class="spinner"></div>
                    </div>
                </button>
            </form>

            <!-- Sign Up Link -->
            <div class="signup-link">
                <p>Don't have an account? <a href="{{ route('register') }}" class="signup-text">Sign up here</a></p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            <p>© 2025 Latino Laundry. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Login Form Handling
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const buttonText = btn.querySelector('.button-text');
            const buttonLoader = btn.querySelector('.button-loader');

            btn.disabled = true;
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'flex';
        });

        // Handle floating labels for inputs with values
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.floating-input');

            inputs.forEach(input => {
                // Check if input has value on load
                if (input.value.trim() !== '') {
                    input.classList.add('has-value');
                }

                // Add has-value class on input
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });

            // Smooth fade-in animation on page load
            const card = document.querySelector('.login-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>