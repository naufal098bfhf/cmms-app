<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <title>Login</title>
</head>

<body class="bg-gradient-to-r from-gray-200 to-blue-200 flex items-center justify-center min-h-screen font-sans">

    <div class="bg-white rounded-3xl shadow-xl w-[768px] max-w-full min-h-[480px] flex overflow-hidden">

        {{-- LEFT (SIGN IN) --}}
        <div class="w-1/2 flex items-center justify-center p-10">
            <form method="POST" action="{{ route('login.post') }}" class="w-full text-center">
                @csrf

                <h1 class="text-2xl font-bold mb-4">Sign In</h1>

                <div class="flex justify-center gap-3 mb-4">
                    <a href="#" class="border rounded-lg w-10 h-10 flex items-center justify-center">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="border rounded-lg w-10 h-10 flex items-center justify-center">
                        <i class="fa-solid fa-globe"></i>
                    </a>
                    <a href="#" class="border rounded-lg w-10 h-10 flex items-center justify-center">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>

                <span class="text-sm text-gray-500">or use your email password</span>

                <input type="email" name="email"
                    class="w-full mt-3 p-2 rounded-lg bg-gray-100 outline-none"
                    placeholder="Email" required>

                <input type="password" name="password"
                    class="w-full mt-2 p-2 rounded-lg bg-gray-100 outline-none"
                    placeholder="Password" required>

                <button class="mt-4 bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg uppercase text-sm font-semibold">
                    Sign In
                </button>
            </form>
        </div>

        {{-- RIGHT (INFO PANEL) --}}
        <div class="w-1/2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white flex flex-col items-center justify-center p-8 text-center">

            <img src="{{ asset('images/logo2.png') }}" class="w-28 mb-4 hover:scale-110 transition">

            <h2 class="text-xl font-bold mb-2">
                Politeknik Industri Petrokimia Banten
            </h2>

            <p class="text-sm leading-relaxed">
                CMMS (Computerized Maintenance Management System) adalah sistem berbasis software
                untuk mengelola pemeliharaan mesin dan aset secara efisien.
            </p>

        </div>

    </div>

</body>
</html>
