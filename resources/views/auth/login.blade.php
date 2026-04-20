<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">

    <title>Login</title>
</head>

<body>

<div class="container">

    <!-- LEFT INFO -->
    <div class="form-container sign-up">
        <div class="cmms-info">
            <h1 class="cmms-title">About CMMS</h1>
            <p class="cmms-desc">
                CMMS adalah sistem untuk manajemen maintenance secara digital.
            </p>
        </div>
    </div>

    <!-- LOGIN FORM -->
    <div class="form-container sign-in">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <h1>Sign In</h1>

            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-solid fa-globe"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>

            <span>or use your email password</span>

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Sign In</button>
        </form>
    </div>

    <!-- RIGHT PANEL -->
    <div class="toggle-container">
        <div class="toggle">
            <img src="{{ asset('images/logo2.png') }}" class="logo-instansi">
            <h3>Politeknik Industri Petrokimia Banten</h3>
        </div>
    </div>

</div>

</body>
</html>
