<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrator Login - Latino Laundry</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --accent-color: #0ea5e9;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --error-color: #dc2626;
            --success-color: #16a34a;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-secondary);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .admin-login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Side - Branding & Info */
        .branding-section {
            flex: 1;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .branding-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(14, 165, 233, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .branding-content {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            margin-bottom: 48px;
        }

        .brand-logo img {
            height: 96px;
            width: auto;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 1.125rem;
            color: #94a3b8;
            margin-bottom: 48px;
            line-height: 1.6;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            color: #cbd5e1;
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .feature-icon svg {
            width: 14px;
            height: 14px;
            color: #60a5fa;
        }

        .feature-text {
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        .branding-footer {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Right Side - Login Form */
        .form-section {
            flex: 0 0 480px;
            background: var(--bg-primary);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: var(--shadow-xl);
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 0.9375rem;
            color: var(--text-secondary);
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #065f46;
            margin-bottom: 32px;
        }

        .security-badge svg {
            width: 14px;
            height: 14px;
        }

        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 3px solid var(--error-color);
            background: #fef2f2;
            color: var(--error-color);
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Form */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            pointer-events: none;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 16px 0 44px;
            border: 1.5px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.9375rem;
            font-family: inherit;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input:hover {
            border-color: #cbd5e1;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input.error {
            border-color: var(--error-color);
        }

        .form-input.error:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .error-text {
            font-size: 0.8125rem;
            color: var(--error-color);
            font-weight: 500;
            margin-top: 4px;
        }

        .forgot-password {
            margin-top: -8px;
            text-align: right;
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Button */
        .login-button {
            width: 100%;
            height: 52px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .login-button:hover:not(:disabled) {
            background: var(--primary-dark);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .login-button:active:not(:disabled) {
            transform: scale(0.98);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
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

        /* Divider */
        .divider {
            margin: 32px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            position: relative;
            background: var(--bg-primary);
            padding: 0 16px;
            font-size: 0.8125rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Copyright */
        .copyright {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .copyright p {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .help-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 12px;
        }

        .help-link {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .help-link:hover {
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .branding-section {
                display: none;
            }

            .form-section {
                flex: 1;
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .form-section {
                padding: 40px 24px;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }

            .brand-title {
                font-size: 2rem;
            }
        }

        /* Animations */
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

        .form-section {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .branding-content {
            animation: fadeInLeft 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="admin-login-wrapper">
        <!-- Left Side: Branding -->
        <div class="branding-section">
            <div class="branding-content">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Latino Laundry">
                </div>

                <h2 class="brand-title">Laundry Management Portal</h2>
                <p class="brand-subtitle">Comprehensive laundry business management for administrators and staff</p>

                <ul class="feature-list">
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="feature-text">Schedule management and order processing</span>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <span class="feature-text">Inventory tracking and stock management</span>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="feature-text">Staff and customer management</span>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="feature-text">Payment processing and reports</span>
                    </li>
                </ul>
            </div>

    
        </div>

        <!-- Right Side: Login Form -->
        <div class="form-section">
            <div class="form-header">
                <h1>Administrator Portal</h1>
                <p>Enter your credentials to access the laundry management system</p>
            </div>

      

            @if (session('status'))
                <div class="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" id="adminLoginForm" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            class="form-input @error('email') error @enderror"
                            placeholder="admin@latinolaundry.com"
                        >
                    </div>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required
                            class="form-input @error('password') error @enderror"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="forgot-password">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot your password?</a>
                    @endif
                </div>

                <button type="submit" id="loginBtn" class="login-button">
                    <span class="button-text">Sign In</span>
                    <div class="button-loader">
                        <div class="spinner"></div>
                    </div>
                </button>
            </form>

            <div class="copyright">
                <p>© 2025 Latino Laundry Management System</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const buttonText = btn.querySelector('.button-text');
            const buttonLoader = btn.querySelector('.button-loader');
            
            btn.disabled = true;
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'flex';
        });
    </script>
</body>
</html>