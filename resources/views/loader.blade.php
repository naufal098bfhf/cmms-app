<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loader CMMS</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Animasi hilang loader */
        #loader {
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #loader.hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Loader -->
    <div id="loader" class="fixed inset-0 bg-white flex flex-col items-center justify-center z-50">
        <div class="animate-bounce">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24">
        </div>
        <p class="mt-4 text-gray-600 font-semibold">Sedang memuat sistem CMMS...</p>
    </div>

    <!-- Konten Utama -->
    <div id="content" class="hidden p-6">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang di Sistem CMMS</h1>
        <p class="mt-2 text-gray-600">Halaman ini sudah dimuat sepenuhnya.</p>
    </div>

    <script>
        // Saat halaman selesai dimuat
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            const content = document.getElementById('content');

            // Tunggu sedikit untuk efek transisi
            setTimeout(() => {
                loader.classList.add('hidden');
                content.classList.remove('hidden');
            }, 500);
        });
    </script>

</body>
</html>
