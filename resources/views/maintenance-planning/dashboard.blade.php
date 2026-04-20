@extends('layouts.app')

@section('content')
@php
use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;

// Stats
$jumlahEquipment = Equipment::count();
$totalTugasTetap = TugasTetap::count();
$totalTugasDarurat = TugasDarurat::count();
$tugasSelesai = TugasTetap::where('status','selesai')->count() + TugasDarurat::where('status','selesai')->count();

// Ambil semua tugas untuk tabel
$tugasDarurat = TugasDarurat::latest()->get();
$tugasTetap   = TugasTetap::latest()->get();
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
      <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2 md:mb-0">Dashboard Maintenance Planning</h2>

        <div class="flex items-center space-x-3 md:space-x-4 relative">
           <!-- Tombol Notifikasi -->
            <button id="notifButton" type="button" class="relative focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082A1.001 1.001 0 0114 18H6a1.001 1.001 0 01-.857-1.555l.857-1.287V11a6 6 0 1112 0v4.158l.857 1.287zM10 22a2 2 0 004 0" />
                </svg>

                @if($notifikasi->count() > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1.5 rounded-full">
                        {{ $notifikasi->count() }}
                    </span>
                @endif
            </button>

      <!-- Dropdown Notifikasi -->
<div id="notifDropdown" class="hidden absolute right-0 top-10 w-72 md:w-80 bg-white rounded-lg shadow-lg border z-50">
    <div class="p-3 border-b font-semibold text-gray-700">Notifikasi Terbaru</div>
    <div class="max-h-60 overflow-y-auto">
        @forelse($notifikasi as $tugas)
            @php
                $jenis = $tugas instanceof \App\Models\TugasTetap ? 'tugas_tetap' : 'tugas_darurat';
                $judul = $jenis === 'tugas_tetap' ? 'Tugas Tetap' : 'Tugas Darurat';
                $pesan = "Tugas dari {$tugas->mekanik->name} menunggu validasi";
            @endphp
            @if(!$tugas->validasi_mp)
                <a href="{{ $jenis === 'tugas_tetap'
                    ? route('maintenance-planning.validasi-tugas.tetap', $tugas->id)
                    : route('maintenance-planning.validasi-tugas.darurat', $tugas->id) }}"
                    class="block p-2 hover:bg-gray-100">
                    <p class="font-semibold text-sm text-gray-800">🔧 {{ $judul }}</p>
                    <p class="text-xs text-gray-600">{{ $pesan }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $tugas->updated_at->diffForHumans() }}</p>
                </a>
            @else
                <div class="p-2 border-b text-gray-500 text-sm">
                    🔧 {{ $judul }} - Sudah divalidasi
                </div>
            @endif
        @empty
            <div class="p-3 text-center text-gray-500 text-sm">Tidak ada notifikasi.</div>
        @endforelse
    </div>
</div>

            <!-- User Info -->
            <div class="flex items-center space-x-2">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/orang.webp') }}"
                     class="w-10 h-10 rounded-full border object-cover"
                     alt="{{ Auth::user()->name }}">
                <span class="font-semibold">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
      <div class="flex items-center space-x-3 bg-red-50 rounded-xl p-3 w-full">
            <div class="bg-red-200 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Jumlah Equipment</p>
                <p class="font-bold text-xl">{{ $jumlahEquipment }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 bg-red-50 rounded-xl p-3 flex-1">
            <div class="bg-red-200 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tugas Tetap</p>
                <p class="font-bold text-xl">{{ $totalTugasTetap }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 bg-red-50 rounded-xl p-3 flex-1">
            <div class="bg-red-200 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zm-3-7l2 2m-16 0l2-2" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tugas Darurat</p>
                <p class="font-bold text-xl">{{ $totalTugasDarurat }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 bg-red-50 rounded-xl p-3 flex-1">
            <div class="bg-red-200 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tugas Selesai</p>
                <p class="font-bold text-xl">{{ $tugasSelesai }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel Tugas Tetap & Darurat -->
    <div class="bg-white rounded-xl shadow p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Daftar Tugas</h3>
        <div class="overflow-x-auto">
    <table class="w-full min-w-[700px] border-collapse text-xs md:text-sm">
                <thead class="bg-red-200 text-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">Jenis Tugas</th>
                        <th class="px-3 py-2 text-left">Pemberi Tugas</th>
                        <th class="px-3 py-2 text-left">Tgl Mulai</th>
                        <th class="px-3 py-2 text-left">Tgl Selesai</th>
                        <th class="px-3 py-2 text-left">Equipment</th>
                        <th class="px-3 py-2 text-left">Lokasi</th>
                        <th class="px-3 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($tugasTetap as $tugas)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-3 py-2">{{ $no++ }}</td>
                            <td class="px-3 py-2">Tugas Tetap</td>
                            <td class="px-3 py-2">{{ $tugas->pemberi_tugas }}</td>
                            <td class="px-3 py-2">{{ $tugas->created_at->format('Y-m-d') }}</td>
                            <td class="px-3 py-2">-</td>
                            <td class="px-3 py-2">{{ $tugas->equipment }}</td>
                            <td class="px-3 py-2">{{ $tugas->lokasi }}</td>
                            <td class="px-3 py-2 text-center">
                                <span class="px-2 py-1 rounded text-white text-xs font-semibold
                                    @if($tugas->status == 'pending') bg-yellow-500
                                    @elseif($tugas->status == 'dikerjakan') bg-blue-500
                                    @else bg-green-600 @endif">
                                    {{ $tugas->status == 'pending' ? 'Release Order' : ucfirst($tugas->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    @foreach($tugasDarurat as $tugas)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-3 py-2">{{ $no++ }}</td>
                            <td class="px-3 py-2">Tugas Darurat</td>
                            <td class="px-3 py-2">{{ $tugas->pemberi_tugas }}</td>
                            <td class="px-3 py-2">{{ $tugas->tgl_mulai }}</td>
                            <td class="px-3 py-2">{{ $tugas->tgl_selesai }}</td>
                            <td class="px-3 py-2">{{ $tugas->equipment }}</td>
                            <td class="px-3 py-2">{{ $tugas->lokasi }}</td>
                            <td class="px-3 py-2 text-center">
                                <span class="px-2 py-1 rounded text-white text-xs font-semibold
                                    @if($tugas->status == 'pending') bg-yellow-500
                                    @elseif($tugas->status == 'dikerjakan') bg-blue-500
                                    @else bg-green-600 @endif">
                                    {{ $tugas->status == 'pending' ? 'Release Order' : ucfirst($tugas->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    @if($tugasTetap->count() + $tugasDarurat->count() == 0)
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500 italic">
                                Belum ada tugas.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const notifBtn = document.getElementById('notifButton');
    const notifDropdown = document.getElementById('notifDropdown');

    notifBtn.addEventListener('click', () => {
        notifDropdown.classList.toggle('hidden');
    });

    window.addEventListener('click', (e) => {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
</script>
@endsection
