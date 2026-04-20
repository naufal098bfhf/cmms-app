@extends('layouts.app')

@section('content')
@php
use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Statistik
$jumlahEquipment = Equipment::count();
$today = Carbon::now();

$tugasHariIni =
    TugasTetap::where('mekanik_id', Auth::id())
        ->where(function ($q) use ($today) {
            $q->where(function ($q2) use ($today) {
                // MINGGUAN
                $q2->where('jenis_tugas', 'mingguan')
                   ->where('hari_mingguan', strtolower($today->locale('id')->dayName));
            })
            ->orWhere(function ($q2) use ($today) {
                // BULANAN
                $q2->where('jenis_tugas', 'bulanan')
                   ->where('tanggal_bulanan', $today->day);
            })
            ->orWhere(function ($q2) use ($today) {
                // TASK YANG SUDAH DIGENERATE (REAL)
                $q2->whereDate('tanggal_mulai', $today);
            });
        })
        ->where('status', '!=', 'selesai')
        ->count()

    +

    TugasDarurat::where('mekanik_id', Auth::id())
        ->whereDate('tgl_mulai', $today)
        ->where('status', '!=', 'selesai')
        ->count();

$tugasPending = TugasTetap::where('mekanik_id', Auth::id())->where('status', 'pending')->count()
    + TugasDarurat::where('mekanik_id', Auth::id())->where('status', 'pending')->count();

$tugasSelesai = TugasTetap::where('mekanik_id', Auth::id())->where('status', 'selesai')->count()
    + TugasDarurat::where('mekanik_id', Auth::id())->where('status', 'selesai')->count();

// Data tabel
$tugasDarurat = TugasDarurat::where('mekanik_id', Auth::id())->latest()->get();
$tugasTetap   = TugasTetap::where('mekanik_id', Auth::id())->latest()->get();
@endphp

<div class="space-y-4 sm:space-y-6 p-2 sm:p-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Dashboard Mekanik</h2>

        <div class="flex items-center gap-2 sm:gap-4 relative w-full sm:w-auto justify-between sm:justify-end">
            <!-- Tombol Notifikasi -->
            <button id="notifButton" type="button" class="relative focus:outline-none">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                             c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.64
                             5.36 6 7.92 6 11v3.159c0 .538-.214 1.055-.595
                             1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1.5 rounded-full">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

           <!-- Dropdown Notifikasi -->
<div id="notifDropdown" class="hidden absolute right-0 top-10 w-full sm:w-80 bg-white rounded-lg shadow-lg border z-50">
    <div class="p-3 border-b font-semibold text-gray-700">Notifikasi Terbaru</div>
    <div class="max-h-60 overflow-y-auto">
        @forelse(auth()->user()->notifications as $notif)
            @php
                $jenis = $notif->data['jenis'] ?? 'tugas_darurat';
                $pesan = $notif->data['pesan'] ?? 'Ada tugas baru yang harus kamu selesaikan.';
                $idTugas = $notif->data['tugas_id'] ?? null;

                // Ambil data tugas berdasarkan jenis (tanpa "use")
                if ($jenis === 'tugas_tetap') {
                    $tugas = \App\Models\TugasTetap::find($idTugas);
                    $link = $tugas ? route('mekanik.tugas-tetap.show', $idTugas) : '#';
                } else {
                    $tugas = \App\Models\TugasDarurat::find($idTugas);
                    $link = $tugas ? route('mekanik.tugas-darurat.show', $idTugas) : '#';
                }
            @endphp

            {{-- Tampilkan notifikasi hanya jika tugas belum selesai --}}
            @if($tugas && $tugas->status !== 'selesai')
                <a href="{{ $link }}" class="block p-3 border-b hover:bg-gray-50 transition">
                    <p class="font-semibold text-sm text-gray-800 flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h6m-3-3v6m9-6a9 9 0 11-18 0
                                     9 9 0 0118 0z"/>
                        </svg>
                        {{ strtoupper(str_replace('_', ' ', $jenis)) }}
                    </p>
                    <p class="text-xs text-gray-600">{{ $pesan }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </a>
            @endif
        @empty
            <div class="p-3 text-center text-gray-500 text-sm">Tidak ada notifikasi.</div>
        @endforelse
    </div>
    <div class="p-2 text-center border-t">
        <form action="{{ route('mekanik.notifikasi.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="text-blue-600 text-sm hover:underline">
                Tandai semua telah dibaca
            </button>
        </form>
    </div>
</div>


            <!-- User Info -->
            <div class="flex items-center space-x-2">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/orang.webp') }}"
                     class="w-9 h-9 rounded-full border object-cover"
                     alt="{{ Auth::user()->name }}">
                <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <!-- Equipment -->
        <div class="bg-white p-3 sm:p-4 rounded-xl shadow flex items-center gap-2 sm:gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 17v-6h6v6m-7 4h8a2 2 0 002-2V7a2
                         2 0 00-2-2h-3l-2-2-2 2H7a2 2 0
                         00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <div>
                <h3 class="text-gray-500 text-sm">Jumlah Equipment</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $jumlahEquipment }}</p>
            </div>
        </div>

        <!-- Tugas Hari Ini -->
        <div class="bg-white p-3 sm:p-4 rounded-xl shadow flex items-center gap-2 sm:gap-3">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5
                         21h14a2 2 0 002-2V7a2 2 0
                         00-2-2H5a2 2 0 00-2 2v12a2
                         2 0 002 2z"/>
            </svg>
            <div>
                <h3 class="text-gray-500 text-sm">Tugas Hari Ini</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $tugasHariIni }}</p>
            </div>
        </div>

        <!-- Tugas Menunggu -->
        <div class="bg-white p-3 sm:p-4 rounded-xl shadow flex items-center gap-2 sm:gap-3">
            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4l3 3m6-3a9 9 0
                         11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-gray-500 text-sm">Tugas Menunggu</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $tugasPending }}</p>
            </div>
        </div>

        <!-- Tugas Selesai -->
        <div class="bg-white p-3 sm:p-4 rounded-xl shadow flex items-center gap-2 sm:gap-3">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <div>
                <h3 class="text-gray-500 text-sm">Tugas Selesai</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $tugasSelesai }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel Tugas -->
    <div class="bg-white rounded-xl shadow p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Daftar Tugas</h3>
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300">
            <table class="w-full border-collapse text-sm">
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
                               @php
                            $label = match($tugas->status) {
                                'pending' => 'Release Order',
                                'release_order' => 'Release Order',
                                'dikerjakan' => 'Dikerjakan',
                                'selesai' => 'Selesai',
                                default => ucfirst($tugas->status),
                            };

                            $warna = match($tugas->status) {
                                'pending', 'release_order' => 'bg-blue-500',
                                'dikerjakan' => 'bg-yellow-500',
                                'selesai' => 'bg-green-600',
                                default => 'bg-gray-400',
                            };
                        @endphp

                        <span class="px-2 py-1 rounded text-white text-xs font-semibold {{ $warna }}">
                            {{ $label }}
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
                                    {{ ucfirst($tugas->status) }}
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

<!-- Script Dropdown -->
<script>
    const notifBtn = document.getElementById('notifButton');
    const notifDropdown = document.getElementById('notifDropdown');
    notifBtn.addEventListener('click', () => notifDropdown.classList.toggle('hidden'));
    window.addEventListener('click', (e) => {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
</script>
@endsection
