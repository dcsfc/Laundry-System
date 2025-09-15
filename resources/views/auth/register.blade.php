<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Latino Laundry System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
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
        .register-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo {
            margin-bottom: 1.5rem;
        }
        .logo img {
            width: 250px;
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
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.75rem 0.65rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
            transition: 0.2s;
            font-size: 0.95rem;
        }
        input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
        }
        .btn-register {
            background: #16a34a;
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
        .btn-register:hover {
            background: #15803d;
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
            color: #16a34a;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Logo Placeholder -->
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Latino Laundry Logo">
        </div>
        <h2>Sign Up for Latino Laundry</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Username</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus />
                </div>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required />
                </div>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input id="password" type="password" name="password" required />
                </div>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" required />
                </div>
            </div>

            <button type="submit" class="btn-register">Sign Up</button>

            <div class="links">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
