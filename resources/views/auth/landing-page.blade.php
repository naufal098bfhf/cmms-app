<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLITEKNIK INDUSTRI PETROKIMIA BANTEN</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        :root {
            --red-main: #b00020;
            --red-dark: #7a0015;
            --blue-accent: #004aad;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
        }

        /* =========================
           UI Enhancement
        ========================= */
        #scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            width: 0%;
            background: linear-gradient(90deg, #b00020, #ff5252);
            z-index: 9999;
            transition: width 0.1s linear;
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s ease;
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

    </style>
</head>
<body class="text-gray-800">

{{-- UI Enhancement: Scroll Progress Bar --}}
<div id="scroll-progress"></div>

{{-- Navbar --}}
<nav class="fixed top-0 left-0 w-full z-40 bg-white/90 backdrop-blur-md shadow-md border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex items-center justify-between">

        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-10 md:h-12 object-contain">
            <h1 class="text-lg md:text-xl font-bold uppercase tracking-wide text-[var(--red-main)] hover:text-[var(--red-dark)] transition-colors duration-300">
                Politeknik Industri Petrokimia
            </h1>
        </div>

        <button id="menu-btn" class="md:hidden text-[var(--red-main)] text-2xl">
    ☰
</button>

        <ul class="hidden md:flex items-center space-x-10 text-sm font-medium text-gray-700">
            <li><a href="#beranda" class="relative group"><span class="group-hover:text-[var(--red-main)]">Beranda</span></a></li>
            <li><a href="#about" class="relative group"><span class="group-hover:text-[var(--red-main)]">Tentang</span></a></li>
            <li><a href="#fungsi" class="relative group"><span class="group-hover:text-[var(--red-main)]">Fungsi</span></a></li>
            <li><a href="#contact" class="relative group"><span class="group-hover:text-[var(--red-main)]">Kontak</span></a></li>
        </ul>

        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-md">
    <ul class="flex flex-col text-center py-4 space-y-4 text-gray-700 font-medium">
        <li><a href="#beranda">Beranda</a></li>
        <li><a href="#about">Tentang</a></li>
        <li><a href="#fungsi">Fungsi</a></li>
        <li><a href="#contact">Kontak</a></li>
    </ul>
</div>

    </div>
</nav>

{{-- Hero Section --}}
<section id="beranda" class="relative h-[90vh] flex items-center bg-cover bg-center bg-no-repeat"
style="background-image: url('{{ asset('images/gedung.jpg') }}');">

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 text-white px-8 md:px-16 lg:px-24 max-w-3xl reveal">
        <p class="text-sm uppercase tracking-wider mb-4 opacity-90">
            POLITEKNIK INDUSTRI PETROKIMIA BANTEN

        </p>
        <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight drop-shadow-lg">

            Computerized Maintenance Management System
        </h1>

        <p class="text-gray-200 text-lg mb-8 leading-relaxed">
            <p class="text-gray-200 text-sm sm:text-base md:text-lg mb-8 leading-relaxed">    <strong>Computerized Maintenance Management System (CMMS)</strong> merupakan sistem digital terpadu
            yang dirancang untuk mengelola seluruh kegiatan pemeliharaan peralatan, mesin, serta fasilitas industri
            secara sistematis dan efisien di <strong>Politeknik Industri Petrokimia Banten</strong>.
        </p>

        <a href="{{ route('login') }}"
           class="inline-block px-8 py-3 bg-[#d6001c] hover:bg-[#b80018] text-white font-semibold rounded-md shadow-lg transition duration-300">
           LOGIN
        </a>
    </div>
</section>

{{-- 3 Info Cards Section --}}
<section id="programs" class="relative -mt-20 z-20 reveal">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 overflow-hidden">
        <!-- Kartu 1 -->
        <div class="p-8 text-center reveal">
            <i class="fa-solid fa-gears text-[var(--red-main)] text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Manajemen Pemeliharaan</h3>
            <p class="text-gray-600 mb-4 text-sm">
                Mengatur dan memantau seluruh kegiatan perawatan mesin, peralatan, serta fasilitas industri secara terencana dan efisien.
            </p>
            <a href="#fungsi" class="text-[var(--red-main)] text-sm font-semibold hover:underline">Pelajari Lebih Lanjut</a>
        </div>

        <!-- Kartu 2 -->
        <div class="p-8 text-center reveal">
            <i class="fa-solid fa-database text-[var(--red-main)] text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Pengelolaan Data Aset</h3>
            <p class="text-gray-600 mb-4 text-sm">
                Menyimpan informasi lengkap tentang aset dan riwayat pemeliharaannya dalam satu sistem digital yang terintegrasi.
            </p>
            <a href="#fungsi" class="text-[var(--red-main)] text-sm font-semibold hover:underline">Pelajari Lebih Lanjut</a>
        </div>

        <!-- Kartu 3 -->
        <div class="p-8 text-center reveal">
            <i class="fa-solid fa-chart-line text-[var(--red-main)] text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Analisis & Pelaporan</h3>
            <p class="text-gray-600 mb-4 text-sm">
                Menyediakan laporan performa aset dan analisis data untuk membantu pengambilan keputusan pemeliharaan yang strategis.
            </p>
            <a href="#fungsi" class="text-[var(--red-main)] text-sm font-semibold hover:underline">Pelajari Lebih Lanjut</a>
        </div>
    </div>
</section>

{{-- About Section --}}
<section id="about" class="py-24 bg-gray-50 reveal">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-12 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
    <div class="md:pr-8">
      <p class="text-[var(--red-main)] uppercase font-semibold mb-3 tracking-wide">Tentang CMMS</p>
      <h2 class="text-3xl md:text-4xl font-bold mb-6 leading-snug text-gray-900">
        Computerized Maintenance Management System (CMMS)
      </h2>
      <p class="text-gray-700 leading-relaxed text-justify text-base md:text-lg">
        <strong>Computerized Maintenance Management System (CMMS)</strong> adalah sistem digital terpadu yang berfungsi
        untuk mengelola, mencatat, dan memantau seluruh aktivitas pemeliharaan aset, mesin, serta fasilitas industri.
        Dengan CMMS, setiap proses perawatan menjadi lebih terstruktur, transparan, dan efisien.
      </p>
      <p class="text-gray-700 leading-relaxed mt-4 text-justify text-base md:text-lg">
        Penerapan sistem ini di lingkungan <strong>Politeknik Industri Petrokimia Banten</strong> membantu meningkatkan
        keandalan peralatan, meminimalkan waktu henti (downtime), serta menciptakan budaya kerja yang produktif dan
        berorientasi pada keberlanjutan industri modern.
      </p>
    </div>

    <div class="flex justify-center md:justify-end">
      <img src="{{ asset('images/cmms.jpeg') }}"
           alt="Ilustrasi CMMS"
           class="w-full max-w-md md:max-w-lg h-auto rounded-2xl shadow-xl object-cover">
    </div>
  </div>
</section>

{{-- Fungsi Section --}}
<section id="fungsi" class="py-24 bg-white border-t border-gray-200 reveal">
  <div class="max-w-6xl mx-auto px-6 md:px-12">
    <div class="text-center mb-16 reveal">
      <p class="text-[var(--red-main)] uppercase font-semibold mb-3 tracking-wide">Fungsi Utama CMMS</p>
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Optimalisasi Pengelolaan dan Pemeliharaan Aset</h2>
      <p class="text-gray-600 mt-4 text-base md:text-lg max-w-3xl mx-auto">
        CMMS dirancang untuk memastikan seluruh proses perawatan, perbaikan, serta pelaporan berjalan efisien dan terdokumentasi secara real-time,
        mendukung terciptanya sistem industri yang handal dan berkelanjutan.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-10 reveal">
      <div class="bg-gray-50 p-8 rounded-2xl shadow-md hover:shadow-xl transition">
        <i class="fa-solid fa-screwdriver-wrench text-[var(--red-main)] text-4xl mb-5"></i>
        <h3 class="text-xl font-semibold mb-3">1. Manajemen Pemeliharaan</h3>
        <p class="text-gray-600 text-justify text-sm md:text-base">
          Mengatur jadwal perawatan rutin, inspeksi, serta tindakan korektif agar setiap aset dan peralatan tetap berfungsi optimal sesuai standar industri.
        </p>
      </div>

      <div class="bg-gray-50 p-8 rounded-2xl shadow-md hover:shadow-xl transition reveal">
        <i class="fa-solid fa-database text-[var(--red-main)] text-4xl mb-5"></i>
        <h3 class="text-xl font-semibold mb-3">2. Pengelolaan Data Aset</h3>
        <p class="text-gray-600 text-justify text-sm md:text-base">
          Menyimpan seluruh informasi aset dalam satu sistem terintegrasi, meliputi riwayat penggunaan, perawatan, dan kinerja aset secara akurat.
        </p>
      </div>

      <div class="bg-gray-50 p-8 rounded-2xl shadow-md hover:shadow-xl transition reveal">
        <i class="fa-solid fa-chart-line text-[var(--red-main)] text-4xl mb-5"></i>
        <h3 class="text-xl font-semibold mb-3">3. Analisis & Pelaporan</h3>
        <p class="text-gray-600 text-justify text-sm md:text-base">
          Menyediakan laporan dan analisis data berbasis performa peralatan untuk mendukung pengambilan keputusan strategis secara cepat dan tepat.
        </p>
      </div>
    </div>

    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 reveal">
      <div class="bg-gray-50 p-8 rounded-2xl shadow-md hover:shadow-xl transition">
        <i class="fa-solid fa-users-gear text-[var(--red-main)] text-4xl mb-5"></i>
        <h3 class="text-xl font-semibold mb-3">4. Koordinasi Tim & Penugasan</h3>
        <p class="text-gray-600 text-justify text-sm md:text-base">
          Memudahkan pembagian tugas antar teknisi, memonitor progres pekerjaan, dan meningkatkan kolaborasi antar divisi dalam sistem pemeliharaan.
        </p>
      </div>

      <div class="bg-gray-50 p-8 rounded-2xl shadow-md hover:shadow-xl transition reveal">
        <i class="fa-solid fa-bolt text-[var(--red-main)] text-4xl mb-5"></i>
        <h3 class="text-xl font-semibold mb-3">5. Efisiensi Energi & Biaya</h3>
        <p class="text-gray-600 text-justify text-sm md:text-base">
          Dengan sistem yang terotomatisasi, CMMS membantu mengurangi pemborosan energi dan biaya operasional, sekaligus meningkatkan efektivitas perawatan jangka panjang.
        </p>
      </div>
    </div>
  </div>
</section>
{{-- Footer CMMS Poltek Petro - Merah Solid Profesional --}}
<footer id="contact" class="bg-[#b30000] text-white pt-16 relative overflow-hidden reveal">

  {{-- Tekstur ringan merah --}}
  <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/dark-mosaic.png')]"></div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 md:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12">

    <!-- Kolom 1: Brand & Deskripsi -->
    <div>
      <div class="flex items-center space-x-3 mb-5">
        <i class="fa-solid fa-gears text-yellow-400 text-3xl"></i>
        <h2 class="text-2xl font-bold tracking-wide text-yellow-400">CMMS POLTEK PETRO</h2>
      </div>
      <p class="text-red-50 leading-relaxed mb-6 text-sm">
        Sistem Manajemen Pemeliharaan Terpadu (CMMS) yang membantu meningkatkan efisiensi dan ketertiban operasional peralatan industri di Politeknik Industri Petrokimia Banten.
      </p>
      <div class="flex space-x-4">
        <a href="#" class="hover:text-yellow-300 transition"><i class="fa-brands fa-facebook text-xl"></i></a>
        <a href="#" class="hover:text-yellow-300 transition"><i class="fa-brands fa-instagram text-xl"></i></a>
        <a href="#" class="hover:text-yellow-300 transition"><i class="fa-brands fa-linkedin text-xl"></i></a>
        <a href="#" class="hover:text-yellow-300 transition"><i class="fa-brands fa-youtube text-xl"></i></a>
      </div>
    </div>

    <!-- Kolom 2: Informasi Kontak -->
    <div>
      <h3 class="text-xl font-semibold mb-6 border-b-2 border-yellow-400 inline-block pb-2 text-yellow-400">Hubungi Kami</h3>
      <ul class="space-y-3 text-red-50 text-sm">
        <li><i class="fa-solid fa-location-dot text-yellow-400 mr-2"></i> Jl. Raya Karang Bolong, Cikoneng, Kec. Anyar, Kabupaten Serang, Banten 42166</li>
        <li><i class="fa-solid fa-phone text-yellow-400 mr-2"></i>  0821-8805-0808</li>
        <li><i class="fa-solid fa-envelope text-yellow-400 mr-2"></i> info@poltekpetrokimia.ac.id</li>
        <li><i class="fa-solid fa-globe text-yellow-400 mr-2"></i> www.poltekpetrokimia.ac.id</li>
      </ul>

      <!-- Tombol Login -->
      <div class="mt-8">
        <a href="{{ route('login') }}"
           class="inline-block bg-yellow-400 text-red-900 font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-yellow-300 hover:-translate-y-1 transition-all duration-300">
          <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk ke Sistem
        </a>
      </div>
    </div>

    <!-- Kolom 3: Google Maps -->
    <div class="rounded-xl overflow-hidden shadow-lg border border-yellow-500 hover:scale-[1.03] transition-transform duration-300">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3805.9602556416426!2d110.25361967480973!3d-6.929503293070343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e705fa787fd3d4b%3A0x3499b6b12b7731a!2sPoliteknik%20Industri%20Furnitur%20dan%20Pengolahan%20Kayu!5e1!3m2!1sid!2sid!4v1761144292281!5m2!1sid!2sid"
        width="100%" height="260" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

  </div>

  <!-- Garis bawah -->
  <div class="border-t border-yellow-500 mt-12 pt-6 text-center text-sm text-yellow-100 bg-[#a00000]/90">
    <p>&copy; 2025 <span class="font-semibold text-yellow-400">CMMS Politeknik Industri Petrokimia Banten</span>. All Rights Reserved.</p>
  </div>
</footer>

{{-- Font Awesome --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

{{-- UI Enhancement Scripts --}}
<script>
    // Scroll Progress (tetap)
    window.addEventListener('scroll', () => {
        const scrollTop = document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        document.getElementById('scroll-progress').style.width = (scrollTop / height) * 100 + '%';
    });

    // Scroll Reveal (REPEATABLE)
    const reveals = document.querySelectorAll('.reveal');

    function handleReveal() {
        const windowHeight = window.innerHeight;

        reveals.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            const elementBottom = el.getBoundingClientRect().bottom;

            if (elementTop < windowHeight - 100 && elementBottom > 100) {
                el.classList.add('active');
            } else {
                el.classList.remove('active'); // ⬅️ INI KUNCINYA
            }
        });
    }

    window.addEventListener('scroll', handleReveal);
    window.addEventListener('load', handleReveal);

    const menuBtn = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});
</script>

</body>
</html>
