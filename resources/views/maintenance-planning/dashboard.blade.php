@extends('layouts.app')

@section('content')

@php
use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;


// =========================================================
// STATS
// =========================================================
$jumlahEquipment = Equipment::count();
$totalTugasTetap = TugasTetap::count();
$totalTugasDarurat = TugasDarurat::count();

$tugasSelesai =
    TugasTetap::where('status', 'selesai')->count()
    +
    TugasDarurat::where('status', 'selesai')->count();

// =========================================================
// DATA TUGAS
// =========================================================
$tugasDarurat = TugasDarurat::latest()->get();
$tugasTetap = TugasTetap::latest()->get();

@endphp

<div class="w-full space-y-4 sm:space-y-5 lg:space-y-6">


{{-- =========================================================
     HEADER
========================================================== --}}
<div
    class="
        bg-white
        rounded-xl
        sm:rounded-2xl
        shadow-sm
        border
        border-gray-100
        p-4
        sm:p-5
        lg:p-6
    "
>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">


        {{-- JUDUL --}}
        <div class="min-w-0">

            <h2
                class="
                    text-xl
                    sm:text-2xl
                    lg:text-3xl
                    font-bold
                    text-gray-800
                    leading-tight
                "
            >
                Dashboard Maintenance Planning
            </h2>

            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Monitoring tugas maintenance dan equipment
            </p>

        </div>


        {{-- BAGIAN KANAN --}}
        <div
            class="
                flex
                items-center
                justify-between
                sm:justify-end
                gap-4
                relative
                w-full
                lg:w-auto
            "
        >


            {{-- =====================================================
                 NOTIFIKASI
            ====================================================== --}}
            <div class="relative">

                <button
                    id="notifButton"
                    type="button"
                    class="
                        relative
                        flex
                        items-center
                        justify-center
                        w-10
                        h-10
                        rounded-xl
                        bg-gray-50
                        hover:bg-gray-100
                        border
                        border-gray-200
                        transition
                        focus:outline-none
                        focus:ring-2
                        focus:ring-red-300
                    "
                    aria-label="Notifikasi"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-gray-700"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.857 17.082A1.001 1.001 0 0114 18H6a1.001 1.001 0 01-.857-1.555l.857-1.287V11a6 6 0 1112 0v4.158l.857 1.287zM10 22a2 2 0 004 0"
                        />
                    </svg>


                    {{-- BADGE NOTIFIKASI --}}
                    @if($notifikasi->count() > 0)

                        <span
                            class="
                                absolute
                                -top-1
                                -right-1
                                min-w-[20px]
                                h-5
                                px-1
                                flex
                                items-center
                                justify-center
                                bg-red-600
                                text-white
                                text-[10px]
                                font-bold
                                rounded-full
                                border-2
                                border-white
                            "
                        >
                            {{ $notifikasi->count() > 99 ? '99+' : $notifikasi->count() }}
                        </span>

                    @endif

                </button>


                {{-- =================================================
                     DROPDOWN NOTIFIKASI
                ================================================== --}}
                <div
                    id="notifDropdown"
                    class="
                        hidden
                        fixed
                        sm:absolute
                        right-3
                        sm:right-0
                        top-[70px]
                        sm:top-12
                        w-[calc(100vw-24px)]
                        sm:w-80
                        max-w-sm
                        bg-white
                        rounded-xl
                        shadow-xl
                        border
                        border-gray-200
                        z-[999]
                        overflow-hidden
                    "
                >

                    {{-- HEADER --}}
                    <div
                        class="
                            px-4
                            py-3
                            border-b
                            border-gray-200
                            flex
                            items-center
                            justify-between
                        "
                    >

                        <div>

                            <p class="font-semibold text-gray-800 text-sm">
                                Notifikasi Terbaru
                            </p>

                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $notifikasi->count() }} notifikasi
                            </p>

                        </div>

                        <button
                            type="button"
                            id="closeNotif"
                            class="
                                text-gray-400
                                hover:text-gray-700
                                text-lg
                            "
                        >
                            ×
                        </button>

                    </div>


                    {{-- LIST NOTIFIKASI --}}
                    <div class="max-h-[60vh] overflow-y-auto">

                        @forelse($notifikasi as $tugas)

                            @php

                                $jenis =
                                    $tugas instanceof \App\Models\TugasTetap
                                    ? 'tugas_tetap'
                                    : 'tugas_darurat';

                                $judul =
                                    $jenis === 'tugas_tetap'
                                    ? 'Tugas Tetap'
                                    : 'Tugas Darurat';

                                $namaMekanik =
                                    optional($tugas->mekanik)->name
                                    ?? 'Mekanik';

                                $pesan =
                                    "Tugas dari {$namaMekanik} menunggu validasi";

                            @endphp


                            @if(!$tugas->validasi_mp)

                                <a
                                    href="{{ $jenis === 'tugas_tetap'
                                        ? route('maintenance-planning.validasi-tugas.tetap', $tugas->id)
                                        : route('maintenance-planning.validasi-tugas.darurat', $tugas->id) }}"
                                    class="
                                        block
                                        px-4
                                        py-3
                                        border-b
                                        border-gray-100
                                        hover:bg-red-50
                                        transition
                                    "
                                >

                                    <div class="flex gap-3">

                                        <div
                                            class="
                                                flex-shrink-0
                                                w-9
                                                h-9
                                                rounded-lg
                                                bg-red-100
                                                flex
                                                items-center
                                                justify-center
                                            "
                                        >
                                            🔧
                                        </div>

                                        <div class="min-w-0 flex-1">

                                            <p
                                                class="
                                                    font-semibold
                                                    text-sm
                                                    text-gray-800
                                                "
                                            >
                                                {{ $judul }}
                                            </p>

                                            <p
                                                class="
                                                    text-xs
                                                    text-gray-600
                                                    mt-0.5
                                                    break-words
                                                "
                                            >
                                                {{ $pesan }}
                                            </p>

                                            <p
                                                class="
                                                    text-[11px]
                                                    text-gray-400
                                                    mt-1
                                                "
                                            >
                                                {{ $tugas->updated_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @else

                                <div
                                    class="
                                        px-4
                                        py-3
                                        border-b
                                        border-gray-100
                                        text-gray-500
                                    "
                                >

                                    <p class="text-sm">
                                        🔧 {{ $judul }}
                                    </p>

                                    <p class="text-xs mt-1">
                                        Sudah divalidasi
                                    </p>

                                </div>

                            @endif

                        @empty

                            <div class="p-6 text-center">

                                <div class="text-3xl mb-2">
                                    🔔
                                </div>

                                <p class="text-sm text-gray-500">
                                    Tidak ada notifikasi.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 USER
            ====================================================== --}}
            <div class="flex items-center gap-2 min-w-0">

                <img
                    src="{{ Auth::user()->photo
                        ? asset('storage/' . Auth::user()->photo)
                        : asset('images/orang.webp') }}"
                    class="
                        w-9
                        h-9
                        sm:w-10
                        sm:h-10
                        rounded-full
                        border
                        border-gray-200
                        object-cover
                        flex-shrink-0
                    "
                    alt="{{ Auth::user()->name }}"
                >

                <div class="min-w-0">

                    <p class="text-[10px] sm:text-xs text-gray-400">
                        Login sebagai
                    </p>

                    <p
                        class="
                            font-semibold
                            text-sm
                            text-gray-800
                            truncate
                            max-w-[130px]
                            sm:max-w-[180px]
                        "
                    >
                        {{ Auth::user()->name }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     STATS CARDS
========================================================== --}}
<div
    class="
        grid
        grid-cols-2
        lg:grid-cols-4
        gap-3
        sm:gap-4
    "
>


    {{-- EQUIPMENT --}}
    <div
        class="
            bg-white
            border
            border-red-100
            rounded-xl
            sm:rounded-2xl
            p-3
            sm:p-4
            shadow-sm
            hover:shadow-md
            transition
        "
    >

        <div class="flex items-center gap-2 sm:gap-3">

            <div
                class="
                    w-9
                    h-9
                    sm:w-11
                    sm:h-11
                    rounded-lg
                    bg-red-100
                    flex
                    items-center
                    justify-center
                    flex-shrink-0
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 sm:h-6 sm:w-6 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7"
                    />
                </svg>

            </div>


            <div class="min-w-0">

                <p
                    class="
                        text-[10px]
                        sm:text-xs
                        lg:text-sm
                        text-gray-500
                        truncate
                    "
                >
                    Jumlah Equipment
                </p>

                <p
                    class="
                        text-lg
                        sm:text-xl
                        lg:text-2xl
                        font-bold
                        text-gray-800
                        mt-0.5
                    "
                >
                    {{ $jumlahEquipment }}
                </p>

            </div>

        </div>

    </div>


    {{-- TUGAS TETAP --}}
    <div
        class="
            bg-white
            border
            border-red-100
            rounded-xl
            sm:rounded-2xl
            p-3
            sm:p-4
            shadow-sm
            hover:shadow-md
            transition
        "
    >

        <div class="flex items-center gap-2 sm:gap-3">

            <div
                class="
                    w-9
                    h-9
                    sm:w-11
                    sm:h-11
                    rounded-lg
                    bg-red-100
                    flex
                    items-center
                    justify-center
                    flex-shrink-0
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 sm:h-6 sm:w-6 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                </svg>

            </div>


            <div class="min-w-0">

                <p class="text-[10px] sm:text-xs lg:text-sm text-gray-500 truncate">
                    Tugas Tetap
                </p>

                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">
                    {{ $totalTugasTetap }}
                </p>

            </div>

        </div>

    </div>


    {{-- TUGAS DARURAT --}}
    <div
        class="
            bg-white
            border
            border-red-100
            rounded-xl
            sm:rounded-2xl
            p-3
            sm:p-4
            shadow-sm
            hover:shadow-md
            transition
        "
    >

        <div class="flex items-center gap-2 sm:gap-3">

            <div
                class="
                    w-9
                    h-9
                    sm:w-11
                    sm:h-11
                    rounded-lg
                    bg-red-100
                    flex
                    items-center
                    justify-center
                    flex-shrink-0
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 sm:h-6 sm:w-6 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zm-3-7l2 2m-16 0l2-2"
                    />
                </svg>

            </div>


            <div class="min-w-0">

                <p class="text-[10px] sm:text-xs lg:text-sm text-gray-500 truncate">
                    Tugas Darurat
                </p>

                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">
                    {{ $totalTugasDarurat }}
                </p>

            </div>

        </div>

    </div>


    {{-- TUGAS SELESAI --}}
    <div
        class="
            bg-white
            border
            border-red-100
            rounded-xl
            sm:rounded-2xl
            p-3
            sm:p-4
            shadow-sm
            hover:shadow-md
            transition
        "
    >

        <div class="flex items-center gap-2 sm:gap-3">

            <div
                class="
                    w-9
                    h-9
                    sm:w-11
                    sm:h-11
                    rounded-lg
                    bg-red-100
                    flex
                    items-center
                    justify-center
                    flex-shrink-0
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 sm:h-6 sm:w-6 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

            </div>


            <div class="min-w-0">

                <p class="text-[10px] sm:text-xs lg:text-sm text-gray-500 truncate">
                    Tugas Selesai
                </p>

                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">
                    {{ $tugasSelesai }}
                </p>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     DAFTAR TUGAS
========================================================== --}}
<div
    class="
        bg-white
        rounded-xl
        sm:rounded-2xl
        shadow-sm
        border
        border-gray-100
        overflow-hidden
    "
>


    {{-- HEADER TABEL --}}
    <div
        class="
            px-4
            sm:px-5
            lg:px-6
            py-4
            border-b
            border-gray-100
            flex
            flex-col
            sm:flex-row
            sm:items-center
            sm:justify-between
            gap-2
        "
    >

        <div>

            <h3
                class="
                    text-base
                    sm:text-lg
                    lg:text-xl
                    font-bold
                    text-gray-800
                "
            >
                Daftar Tugas
            </h3>

            <p class="text-xs text-gray-400 mt-1">
                Daftar tugas tetap dan tugas darurat
            </p>

        </div>


        {{-- TOTAL --}}
        <div
            class="
                self-start
                sm:self-auto
                px-3
                py-1.5
                rounded-lg
                bg-red-50
                text-red-600
                text-xs
                font-semibold
            "
        >
            Total:
            {{ $tugasTetap->count() + $tugasDarurat->count() }}
        </div>

    </div>


    {{-- INFO MOBILE --}}
    <div
        class="
            block
            sm:hidden
            px-4
            py-2.5
            bg-gray-50
            border-b
            border-gray-100
            text-[11px]
            text-gray-500
        "
    >
        ← Geser tabel ke kiri/kanan untuk melihat semua data →
    </div>


    {{-- TABEL --}}
    <div class="w-full overflow-x-auto">

        <table
            class="
                w-full
                min-w-[900px]
                border-collapse
                text-xs
                sm:text-sm
            "
        >

            <thead class="bg-red-100 text-gray-800">

                <tr>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        No
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        Jenis Tugas
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        Pemberi Tugas
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        Tgl Mulai
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        Tgl Selesai
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        Equipment
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-left font-semibold whitespace-nowrap">
                        Lokasi
                    </th>

                    <th class="px-3 sm:px-4 py-3 text-center font-semibold whitespace-nowrap">
                        Status
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

                @php
                    $no = 1;
                @endphp


                {{-- =================================================
                     TUGAS TETAP
                ================================================== --}}
                @foreach($tugasTetap as $tugas)

                    <tr class="hover:bg-red-50 transition">

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $no++ }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">

                            <span
                                class="
                                    inline-flex
                                    px-2.5
                                    py-1
                                    rounded-full
                                    bg-purple-100
                                    text-purple-700
                                    text-[10px]
                                    sm:text-xs
                                    font-semibold
                                "
                            >
                                Tugas Tetap
                            </span>

                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $tugas->pemberi_tugas ?? '-' }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ optional($tugas->created_at)->format('Y-m-d') ?? '-' }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            -
                        </td>

                        <td
                            class="
                                px-3
                                sm:px-4
                                py-3
                                max-w-[220px]
                            "
                        >
                            <div
                                class="truncate"
                                title="{{ $tugas->equipment ?? '-' }}"
                            >
                                {{ $tugas->equipment ?? '-' }}
                            </div>
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $tugas->lokasi ?? '-' }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 text-center">

                            @if($tugas->status == 'pending')

                                <span
                                    class="
                                        inline-flex
                                        px-2.5
                                        py-1
                                        rounded-full
                                        bg-yellow-100
                                        text-yellow-700
                                        text-[10px]
                                        sm:text-xs
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    Release Order
                                </span>

                            @elseif($tugas->status == 'dikerjakan')

                                <span
                                    class="
                                        inline-flex
                                        px-2.5
                                        py-1
                                        rounded-full
                                        bg-blue-100
                                        text-blue-700
                                        text-[10px]
                                        sm:text-xs
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    Dikerjakan
                                </span>

                            @else

                                <span
                                    class="
                                        inline-flex
                                        px-2.5
                                        py-1
                                        rounded-full
                                        bg-green-100
                                        text-green-700
                                        text-[10px]
                                        sm:text-xs
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    {{ ucfirst($tugas->status) }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach


                {{-- =================================================
                     TUGAS DARURAT
                ================================================== --}}
                @foreach($tugasDarurat as $tugas)

                    <tr class="hover:bg-red-50 transition">

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $no++ }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">

                            <span
                                class="
                                    inline-flex
                                    px-2.5
                                    py-1
                                    rounded-full
                                    bg-red-100
                                    text-red-700
                                    text-[10px]
                                    sm:text-xs
                                    font-semibold
                                "
                            >
                                Tugas Darurat
                            </span>

                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $tugas->pemberi_tugas ?? '-' }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $tugas->tgl_mulai ?? '-' }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $tugas->tgl_selesai ?? '-' }}
                        </td>

                        <td
                            class="
                                px-3
                                sm:px-4
                                py-3
                                max-w-[220px]
                            "
                        >

                            <div
                                class="truncate"
                                title="{{ $tugas->equipment ?? '-' }}"
                            >
                                {{ $tugas->equipment ?? '-' }}
                            </div>

                        </td>

                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                            {{ $tugas->lokasi ?? '-' }}
                        </td>

                        <td class="px-3 sm:px-4 py-3 text-center">

                            @if($tugas->status == 'pending')

                                <span
                                    class="
                                        inline-flex
                                        px-2.5
                                        py-1
                                        rounded-full
                                        bg-yellow-100
                                        text-yellow-700
                                        text-[10px]
                                        sm:text-xs
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    Release Order
                                </span>

                            @elseif($tugas->status == 'dikerjakan')

                                <span
                                    class="
                                        inline-flex
                                        px-2.5
                                        py-1
                                        rounded-full
                                        bg-blue-100
                                        text-blue-700
                                        text-[10px]
                                        sm:text-xs
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    Dikerjakan
                                </span>

                            @else

                                <span
                                    class="
                                        inline-flex
                                        px-2.5
                                        py-1
                                        rounded-full
                                        bg-green-100
                                        text-green-700
                                        text-[10px]
                                        sm:text-xs
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    {{ ucfirst($tugas->status) }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach


                {{-- =================================================
                     KOSONG
                ================================================== --}}
                @if(
                    $tugasTetap->count()
                    +
                    $tugasDarurat->count()
                    == 0
                )

                    <tr>

                        <td
                            colspan="8"
                            class="
                                px-4
                                py-10
                                text-center
                                text-gray-500
                            "
                        >

                            <div class="flex flex-col items-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="
                                        w-10
                                        h-10
                                        text-gray-300
                                        mb-2
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>

                                <p class="text-sm">
                                    Belum ada tugas.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endif

            </tbody>

        </table>

    </div>


    {{-- FOOTER --}}
    <div
        class="
            px-4
            sm:px-5
            lg:px-6
            py-3
            bg-gray-50
            border-t
            border-gray-100
            text-xs
            text-gray-500
        "
    >

        Menampilkan
        <span class="font-semibold text-gray-700">
            {{ $tugasTetap->count() + $tugasDarurat->count() }}
        </span>
        tugas.

    </div>

</div>

</div>

{{-- =============================================================
JAVASCRIPT NOTIFIKASI
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const notifBtn = document.getElementById('notifButton');
    const notifDropdown = document.getElementById('notifDropdown');
    const closeNotif = document.getElementById('closeNotif');


    if (!notifBtn || !notifDropdown) {
        return;
    }


    // Buka / tutup notifikasi
    notifBtn.addEventListener('click', function (event) {

        event.stopPropagation();

        notifDropdown.classList.toggle('hidden');

    });


    // Tombol close
    if (closeNotif) {

        closeNotif.addEventListener('click', function (event) {

            event.stopPropagation();

            notifDropdown.classList.add('hidden');

        });

    }


    // Klik di luar dropdown
    document.addEventListener('click', function (event) {

        if (
            !notifDropdown.contains(event.target)
            &&
            !notifBtn.contains(event.target)
        ) {

            notifDropdown.classList.add('hidden');

        }

    });

});

</script>

@endsection
