@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-4 sm:p-6">

    <!-- Judul -->
    <h2 class="text-lg sm:text-2xl font-bold mb-4 text-gray-800">
        Detail Tugas Darurat
    </h2>

    <!-- Isi -->
    <div class="space-y-3 text-sm sm:text-base">

        <div class="flex flex-col sm:flex-row sm:gap-2">
            <strong class="min-w-[150px]">Pemberi Tugas:</strong>
            <span>{{ $tugas->pemberi_tugas }}</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-2">
            <strong class="min-w-[150px]">Equipment:</strong>
            <span class="break-words">{{ $tugas->equipment }}</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-2">
            <strong class="min-w-[150px]">Lokasi:</strong>
            <span class="break-words">{{ $tugas->lokasi }}</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-2">
            <strong class="min-w-[150px]">Tanggal Mulai:</strong>
            <span>{{ $tugas->tgl_mulai }}</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-2">
            <strong class="min-w-[150px]">Tanggal Selesai:</strong>
            <span>{{ $tugas->tgl_selesai }}</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-2 items-start sm:items-center">
            <strong class="min-w-[150px]">Status:</strong>
            <span class="px-2 py-1 rounded text-white text-xs sm:text-sm
                @if($tugas->status == 'pending') bg-yellow-500
                @elseif($tugas->status == 'dikerjakan') bg-blue-500
                @else bg-green-600 @endif">
                {{ ucfirst($tugas->status) }}
            </span>
        </div>

    </div>

    <!-- Tombol -->
    <div class="mt-6">
        <a href="{{ route('mekanik.dashboard') }}"
           class="inline-block text-sm sm:text-base text-blue-600 hover:underline">
            ← Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
