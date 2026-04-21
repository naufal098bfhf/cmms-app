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

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center md:items-start">
 <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Dashboard Admin</h2>


        <div class="flex items-center space-x-6 relative">
            <!-- Tombol Notifikasi -->
            <button id="notifButton" type="button" class="relative focus:outline-none hover:scale-105 transition-transform duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082A1.001 1.001 0 0114 18H6a1.001 1.001 0 01-.857-1.555l.857-1.287V11a6 6 0 1112 0v4.158l.857 1.287zM10 22a2 2 0 004 0" />
                </svg>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1.5 rounded-full shadow-md">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <!-- Dropdown Notifikasi -->
            <div id="notifDropdown" class="hidden absolute right-0 top-10 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                <div class="p-3 border-b font-semibold text-gray-800 bg-gray-50"> Notifikasi Terbaru</div>
                <div class="max-h-60 overflow-y-auto divide-y divide-gray-100">
                    @forelse(auth()->user()->notifications as $notif)
                        @php
                            $jenis = $notif->data['jenis'] ?? 'tugas_darurat';
                            $judul = $notif->data['judul'] ?? ucfirst(str_replace('_', ' ', $jenis));
                            $pesan = $notif->data['pesan'] ?? 'Ada tugas baru yang harus diselesaikan.';
                            $idTugas = $notif->data['tugas_id'] ?? null;
                            $link = $idTugas
                                ? ($jenis === 'tugas_tetap'
                                    ? route('admin.tugas-tetap.show', $idTugas)
                                    : route('admin.kelola-tugas.tugas-darurat.show', $idTugas))
                                : '#';
                        @endphp
                        <a href="{{ $link }}" class="block p-3 hover:bg-red-50 transition-all duration-150">
                            <p class="font-semibold text-sm text-gray-800">
                                🔧 {{ strtoupper(str_replace('_', ' ', $jenis)) }}
                            </p>
                            <p class="text-xs text-gray-600">{{ $pesan }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="p-3 text-center text-gray-500 text-sm italic">Tidak ada notifikasi.</div>
                    @endforelse
                </div>
                <div class="p-2 text-center border-t bg-gray-50">
                    <form action="{{ route('mekanik.notifikasi.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-blue-600 text-sm hover:underline font-medium">
                            Tandai semua telah dibaca
                        </button>
                    </form>
                </div>
            </div>

            <!-- User Info -->
            <div class="flex items-center space-x-3 bg-gray-50 px-3 py-2 rounded-full shadow-inner">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/orang.webp') }}"
                     class="w-10 h-10 rounded-full border-2 border-red-200 object-cover"
                     alt="{{ Auth::user()->name }}">
                <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['title' => 'Jumlah Equipment', 'value' => $jumlahEquipment, 'icon' => 'M3 7l9-4 9 4-9 4-9-4z M3 7v10l9 4 9-4V7', 'color' => 'red'],
                ['title' => 'Tugas Tetap', 'value' => $totalTugasTetap, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'blue'],
                ['title' => 'Tugas Darurat', 'value' => $totalTugasDarurat, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0', 'color' => 'orange'],
                ['title' => 'Tugas Selesai', 'value' => $tugasSelesai, 'icon' => 'M5 13l4 4L19 7', 'color' => 'green'],
            ];
        @endphp

        @foreach($stats as $item)
        <div class="bg-white p-5 rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-{{ $item['color'] }}-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $item['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ $item['title'] }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $item['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-400 text-white font-semibold text-lg rounded-t-2xl">
            Daftar Tugas
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Jenis Tugas</th>
                        <th class="px-4 py-3 text-left">Pemberi Tugas</th>
                        <th class="px-4 py-3 text-left">Tgl Mulai</th>
                        <th class="px-4 py-3 text-left">Tgl Selesai</th>
                        <th class="px-4 py-3 text-left">Equipment</th>
                        <th class="px-4 py-3 text-left">Lokasi</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @php $no=1; @endphp
                    @foreach($tugasTetap as $tugas)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $no++ }}</td>
                            <td class="px-4 py-3">Tugas Tetap</td>
                            <td class="px-4 py-3">{{ $tugas->pemberi_tugas }}</td>
                            <td class="px-4 py-3">{{ $tugas->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">-</td>
                            <td class="px-4 py-3">{{ $tugas->equipment }}</td>
                            <td class="px-4 py-3">{{ $tugas->lokasi }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-white text-xs font-semibold shadow-sm
                                    @if($tugas->status == 'pending') bg-yellow-500
                                    @elseif($tugas->status == 'dikerjakan') bg-blue-500
                                    @else bg-green-600 @endif">
                                    {{ $tugas->status == 'pending' ? 'Release Order' : ucfirst($tugas->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    @foreach($tugasDarurat as $tugas)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $no++ }}</td>
                            <td class="px-4 py-3">Tugas Darurat</td>
                            <td class="px-4 py-3">{{ $tugas->pemberi_tugas }}</td>
                            <td class="px-4 py-3">{{ $tugas->tgl_mulai }}</td>
                            <td class="px-4 py-3">{{ $tugas->tgl_selesai }}</td>
                            <td class="px-4 py-3">{{ $tugas->equipment }}</td>
                            <td class="px-4 py-3">{{ $tugas->lokasi }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-white text-xs font-semibold shadow-sm
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
                            <td colspan="8" class="text-center py-5 text-gray-500 italic">
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
