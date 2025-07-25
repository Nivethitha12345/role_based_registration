<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial;
            margin: 50px;
            background-color: #f8f8f8;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        p {
            text-align: center;
            margin-top: 15px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
        .forgot {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <h2 style="text-align: center;">Login</h2>

        {{-- Display validation error --}}
        @if ($errors->any())
            <div class="message">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Display success message --}}
        @if (session('success'))
            <div class="message" style="color: green;">
                {{ session('success') }}
            </div>
        @endif

        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>

        {{-- Forgot Password Link --}}
        <div class="forgot">
            <a href="{{ route('password.request') }}">Forgot Your Password?</a>
        </div>

        <p>Don't have an account? <a href="{{ route('signup') }}">Sign up here</a></p>
    </form>

</body>
</html>
