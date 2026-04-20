<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLITEKNIK PETROKIMIA BANTEN</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>[x-cloak]{ display:none !important; }</style>
</head>
<body class="bg-gray-100">


{{-- Layout khusus dashboard/admin/mekanik/history/maintenance planning --}}
@if(request()->is('admin*') || request()->is('mekanik*') || request()->is('history*') || request()->is('maintenance-planning*'))
<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden relative">
    <!-- OVERLAY (HP) -->
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
     x-transition.opacity>
</div>
    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="w-64 bg-red-700 text-white flex flex-col h-full fixed md:relative z-50 transform transition-transform duration-300 ease-in-out">

        <!-- Logo -->
        <div class="p-4 text-center border-b border-red-500">
            <img src="{{ asset('images/logo2.png') }}" class="mx-auto w-16 mb-2" alt="Logo">
            <h1 class="text-lg font-bold leading-tight">
                POLITEKNIK INDUSTRI<br>PETROKIMIA BANTEN
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 mt-4 space-y-1 px-2 overflow-y-auto">
            @auth
                {{-- Dashboard --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.dashboard') ? 'bg-red-900' : 'hover:bg-red-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7m6 0v14" />
                        </svg>
                        Dashboard
                    </a>
                @elseif(auth()->user()->role === 'mekanik')
                    <a href="{{ route('mekanik.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('mekanik.dashboard') ? 'bg-red-900' : 'hover:bg-red-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7m6 0v14" />
                        </svg>
                        Dashboard
                    </a>
                @elseif(auth()->user()->role === 'maintenance-planning')
                    <a href="{{ route('maintenance-planning.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('maintenance-planning.dashboard') ? 'bg-red-900' : 'hover:bg-red-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7m6 0v14" />
                        </svg>
                        Dashboard
                    </a>
                @endif

                {{-- Kelola User (Admin only) --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.users.*') ? 'bg-red-900' : 'hover:bg-red-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A8 8 0 1116.88 6.196 8 8 0 015.12 17.804z" />
                        </svg>
                        Kelola User
                    </a>
                @endif

               {{-- Admin --}}
@if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.kelola-equipment.index') }}"
        class="flex items-center px-4 py-2 rounded-lg transition
        {{ request()->routeIs('admin.kelola-equipment.*') ? 'bg-red-900' : 'hover:bg-red-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 13V7a2 2 0 00-2-2h-3V4a1 1 0 00-2 0v1H9V4a1 1 0 00-2 0v1H4a2 2 0 00-2 2v6m0 0v6a2 2 0 002 2h16a2 2 0 002-2v-6m-18 0h18" />
        </svg>
        Kelola Data Equipment
    </a>
@endif

{{-- Maintenance Planning --}}
@if(auth()->user()->role === 'maintenance-planning')
    <a href="{{ route('maintenance-planning.kelola-equipment.index') }}"
        class="flex items-center px-4 py-2 rounded-lg transition
        {{ request()->routeIs('maintenance-planning.kelola-equipment.*') ? 'bg-red-900' : 'hover:bg-red-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 13V7a2 2 0 00-2-2h-3V4a1 1 0 00-2 0v1H9V4a1 1 0 00-2 0v1H4a2 2 0 00-2 2v6m0 0v6a2 2 0 002 2h16a2 2 0 002-2v-6m-18 0h18" />
        </svg>
        Kelola Data Equipment
    </a>
@endif

                {{-- Kelola Tugas Dropdown --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button type="button" @click="open = !open"
                        class="flex items-center w-full px-4 py-2 rounded-lg justify-between
                        transition hover:bg-red-600">
                        <span class="flex items-center font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6M4 6h16M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6" />
                            </svg>
                            Kelola Tugas
                        </span>
                        <svg :class="open ? 'rotate-180' : ''"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transition-transform"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition
                        class="ml-6 mt-1 space-y-1 border-l border-red-600 pl-3">

                        {{-- Admin --}}
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.kelola-tugas.tugas-tetap.index') }}"
                                class="block px-3 py-2 rounded-md text-sm
                                {{ request()->routeIs('admin.kelola-tugas.tugas-tetap.*')
                                    ? 'bg-red-700 text-white font-semibold'
                                    : 'hover:bg-red-700 hover:text-white text-gray-300' }}">
                                Tugas Tetap
                            </a>
                            <a href="{{ route('admin.kelola-tugas.tugas-darurat.index') }}"
                                class="block px-3 py-2 rounded-md text-sm
                                {{ request()->routeIs('admin.kelola-tugas.tugas-darurat.*')
                                    ? 'bg-red-700 text-white font-semibold'
                                    : 'hover:bg-red-700 hover:text-white text-gray-300' }}">
                                Tugas Darurat
                            </a>
                        @endif

                        {{-- Maintenance Planning --}}
                        @if(auth()->user()->role === 'maintenance-planning')
                            <a href="{{ route('maintenance-planning.kelola-tugas.tugas-tetap.index') }}"
                                class="block px-3 py-2 rounded-md text-sm
                                {{ request()->routeIs('maintenance-planning.kelola-tugas.tugas-tetap.*')
                                    ? 'bg-red-700 text-white font-semibold'
                                    : 'hover:bg-red-700 hover:text-white text-gray-300' }}">
                                Tugas Tetap
                            </a>
                            <a href="{{ route('maintenance-planning.kelola-tugas.tugas-darurat.index') }}"
                                class="block px-3 py-2 rounded-md text-sm
                                {{ request()->routeIs('maintenance-planning.kelola-tugas.tugas-darurat.*')
                                    ? 'bg-red-700 text-white font-semibold'
                                    : 'hover:bg-red-700 hover:text-white text-gray-300' }}">
                                Tugas Darurat
                            </a>
                        @endif

                        {{-- Mekanik --}}
                        @if(auth()->user()->role === 'mekanik')
                            <a href="{{ route('mekanik.tugas-tetap.index') }}"
                                class="block px-3 py-2 rounded-md text-sm
                                {{ request()->routeIs('mekanik.tugas-tetap.*')
                                    ? 'bg-red-700 text-white font-semibold'
                                    : 'hover:bg-red-700 hover:text-white text-gray-300' }}">
                                Tugas Tetap
                            </a>
                            <a href="{{ route('mekanik.tugas-darurat.index') }}"
                                class="block px-3 py-2 rounded-md text-sm
                                {{ request()->routeIs('mekanik.tugas-darurat.*')
                                    ? 'bg-red-700 text-white font-semibold'
                                    : 'hover:bg-red-700 hover:text-white text-gray-300' }}">
                                Tugas Darurat
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Riwayat Tugas (Admin & Maintenance Planning & Mekanik) --}}
                @php
                    $riwayatRoute = match(auth()->user()->role) {
                        'admin' => 'admin.riwayat-tugas.index',
                        'maintenance-planning' => 'maintenance-planning.riwayat-tugas.index',
                        'mekanik' => 'mekanik.riwayat-tugas.index',
                        default => '#',
                    };
                @endphp

                <a href="{{ $riwayatRoute !== '#' ? route($riwayatRoute) : '#' }}"
                class="flex items-center px-4 py-2 rounded-lg transition
                {{ request()->routeIs('admin.riwayat-tugas.*') || request()->routeIs('maintenance-planning.riwayat-tugas.*') || request()->routeIs('mekanik.riwayat-tugas.*')
                    ? 'bg-red-900 text-white'
                    : 'hover:bg-red-600 text-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Tugas
                </a>
            @endauth
        </nav>

        <!-- Logout -->
        @auth
            <div class="border-t border-red-600 mt-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center px-4 py-3 text-white hover:bg-red-600 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m0 14a9 9 0 110-18 9 9 0 010 18z" />
                    </svg>
                    Logout
                </a>
            </div>
        @endauth
    </aside>

    <!-- Main Content -->
    <main
        :class="sidebarOpen ? 'md:ml-64' : 'ml-0'"
        class="flex-1 p-6 overflow-y-auto transition-all duration-300 bg-gray-50 min-h-screen">

        <!-- Tombol Toggle Sidebar (Mobile) -->
        <button
            @click="sidebarOpen = true"
            class="p-2 mb-4 bg-red-600 text-white rounded-md md:hidden focus:outline-none focus:ring-2 focus:ring-red-400">
            ☰ Menu
        </button>

        @yield('content')
    </main>
</div>

{{-- Layout Publik --}}
@else
<main class="w-full p-0 m-0">
    @yield('content')
</main>
@endif

</body>
</html>
