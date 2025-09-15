<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Latino Laundry System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box; /* ensures padding/borders don't overflow */
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo {
            margin-bottom: 1rem;
        }
        .logo img {
            width: 250px; /* Adjust size as needed */
            height: auto;
        }
        h2 {
            margin-bottom: 1.5rem;
            color: #111827;
        }
        .form-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #374151;
            font-weight: 500;
        }
        .input-wrapper {
            position: relative;
            width: 100%;
        }
        .input-wrapper i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.75rem 0.65rem 2.5rem; /* padding-left for icon */
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
            transition: 0.2s;
            font-size: 0.95rem;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .btn-login {
            background: #2563eb;
            color: white;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 6px;
            width: 100%;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 0.5rem;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #1d4ed8;
        }
        .error {
            color: #dc2626;
            margin-top: 0.3rem;
            font-size: 0.9rem;
        }
        .links {
            margin-top: 1.2rem;
            font-size: 0.9rem;
            text-align: center;
        }
        .links a {
            color: #2563eb;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo Placeholder -->
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Latino Laundry Logo">
        </div>
        <h2>Login to Latino Laundry</h2>

        <!-- Session Status -->
        @if (session('status'))
            <div class="error">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
                </div>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input id="password" type="password" name="password" required />
                </div>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Log in</button>

            <div class="links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
                <br>
                <a href="{{ route('register') }}">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</body>
</html>
