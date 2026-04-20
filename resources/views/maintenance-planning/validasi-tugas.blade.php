@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-700 mb-6">Validasi Tugas Mekanik</h2>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    {{-- Tugas Tetap --}}
    <h3 class="text-xl font-semibold text-gray-700 mb-3">Tugas Tetap</h3>
    <table class="w-full border border-gray-200 rounded mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border-b">ID</th>
                <th class="p-2 border-b">Mekanik</th>
                <th class="p-2 border-b">Jenis Tugas</th>
                <th class="p-2 border-b">Status</th>
                <th class="p-2 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tugasTetap as $tugas)
                <tr>
                    <td class="p-2 border-b">{{ $tugas->id }}</td>
                    <td class="p-2 border-b">{{ $tugas->mekanik->name ?? '-' }}</td>
                    <td class="p-2 border-b">{{ ucfirst($tugas->jenis_tugas) }}</td>
                    <td class="p-2 border-b">
                        <span class="px-2 py-1 rounded text-white {{ $tugas->status=='selesai' ? 'bg-blue-600' : 'bg-gray-400' }}">
                            {{ ucfirst($tugas->status) }}
                        </span>
                        @if(!$tugas->validasi_mp)
                            <span class="px-2 py-1 ml-2 rounded bg-yellow-300 text-yellow-800 text-xs">Menunggu Validasi</span>
                        @endif
                    </td>
                    <td class="p-2 border-b">
                    @if(!$tugas->validasi_mp)
                        <form action="{{ route('validasi-tugas.darurat', $tugas->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit">Validasi</button>
                    </form>
                    @else
                        <span class="text-gray-500 text-sm">Sudah divalidasi</span>
                    @endif
                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-2 text-center text-gray-500">Tidak ada tugas untuk divalidasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tugas Darurat --}}
    <h3 class="text-xl font-semibold text-gray-700 mb-3">Tugas Darurat</h3>
    <table class="w-full border border-gray-200 rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border-b">ID</th>
                <th class="p-2 border-b">Mekanik</th>
                <th class="p-2 border-b">Jenis Tugas</th>
                <th class="p-2 border-b">Status</th>
                <th class="p-2 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tugasDarurat as $tugas)
                <tr>
                    <td class="p-2 border-b">{{ $tugas->id }}</td>
                    <td class="p-2 border-b">{{ $tugas->mekanik->name ?? '-' }}</td>
                    <td class="p-2 border-b">{{ ucfirst($tugas->jenis_tugas) }}</td>
                    <td class="p-2 border-b">
                        <span class="px-2 py-1 rounded text-white {{ $tugas->status=='selesai' ? 'bg-blue-600' : 'bg-gray-400' }}">
                            {{ ucfirst($tugas->status) }}
                        </span>
                        @if(!$tugas->validasi_mp)
                            <span class="px-2 py-1 ml-2 rounded bg-yellow-300 text-yellow-800 text-xs">Menunggu Validasi</span>
                        @endif
                    </td>
                    <td class="p-2 border-b">
                        @if(!$tugas->validasi_mp)
                        <form action="{{ route('maintenance-planning.validasi-tugas-darurat', $tugas->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                Validasi
                            </button>
                        </form>
                        @else
                            <span class="text-gray-500 text-sm">Sudah divalidasi</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-2 text-center text-gray-500">Tidak ada tugas untuk divalidasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
