@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Validasi Tugas Tetap</h2>

    <div class="border p-4 rounded-lg bg-gray-50 space-y-2">
        <p><strong>Mekanik:</strong> {{ $tugas->mekanik->name }}</p>
        <p><strong>Equipment:</strong> {{ $tugas->equipmentRelasi->nama_equipment }}</p>
        <p><strong>Status:</strong> {{ $tugas->status }}</p>

        {{-- Foto Bukti --}}
        @if(!empty($tugas->bukti_foto))
            <div class="mt-2">
                <p class="font-medium">Foto Bukti:</p>
                <a href="{{ asset('storage/' . $tugas->bukti_foto) }}" target="_blank">
                    <img src="{{ asset('storage/' . $tugas->bukti_foto) }}" alt="Bukti Foto" class="mt-1 rounded-lg border border-gray-300 max-w-xs">
                </a>
                <p class="text-sm text-gray-500 mt-1">Klik gambar untuk memperbesar.</p>
            </div>
        @else
            <p class="text-gray-400 italic">Belum ada foto bukti yang diupload.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('maintenance-planning.validasi-tugas.tetap.update', $tugas->id) }}" class="mt-5">
        @csrf
        @method('PUT')
        <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Validasi Sekarang
        </button>
    </form>
</div>
@endsection
