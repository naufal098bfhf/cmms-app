@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-md">
    <!-- Judul -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Riwayat Tugas</h2>
    </div>

    <!-- Filter + Pencarian -->
    <div class="flex flex-col md:flex-row items-center justify-center gap-3 mb-8">
        <form method="GET" action="{{ route('admin.riwayat-tugas.index') }}" class="flex flex-col md:flex-row items-center gap-3 flex-wrap">
            <input type="date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:outline-none">
            <span class="text-gray-500 text-sm">sampai</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:outline-none">

            <!-- Pencarian teks -->
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, atau equipment..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:outline-none">

            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-medium px-4 py-2 rounded-lg transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-rose-100 text-gray-800">
                <tr class="text-center">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Pemberi Tugas</th>
                    <th class="p-2 border">Jenis</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Mekanik</th>
                    <th class="p-2 border">Equipment</th>
                    <th class="p-2 border">Tag</th>
                    <th class="p-2 border">EQ Class</th>
                    <th class="p-2 border">BoM</th>
                    <th class="p-2 border">Task</th>
                    <th class="p-2 border">Lokasi</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Foto Bukti</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($riwayat as $index => $t)
                    <tr class="text-center border-b hover:bg-rose-50 transition">
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $t['pemberi_tugas'] }}</td>
                        <td class="p-2 border font-medium text-gray-700">{{ $t['jenis'] }}</td>
                        <td class="p-2 border">{{ $t['tgl_mulai'] }}</td>
                        <td class="p-2 border">{{ $t['nama_mekanik'] }}</td>
                        <td class="p-2 border">{{ $t['equipment'] }}</td>
                        <td class="p-2 border">{{ $t['tag_number'] }}</td>
                        <td class="p-2 border">{{ $t['eq_class'] }}</td>
                        <td class="p-2 border">{{ $t['bom'] ?? '-' }}</td>
                        <td class="p-2 border">{{ $t['task_list'] }}</td>
                        <td class="p-2 border">{{ $t['lokasi'] }}</td>

                        {{-- Kolom Status --}}
                        <td class="p-2 border text-center">
                            @php
                                $status = strtolower($t['status']);
                                $validasi = isset($t['validasi_mp']) && ($t['validasi_mp'] == 1 || $t['validasi_mp'] === true || $t['validasi_mp'] === '1');

                                if ($status === 'pending') {
                                    $statusTampil = 'Release Order';
                                } elseif ($status === 'dikerjakan') {
                                    $statusTampil = 'Dikerjakan';
                                } elseif ($status === 'selesai' && !$validasi) {
                                    $statusTampil = 'Menunggu Validasi MP';
                                } else {
                                    $statusTampil = 'Selesai';
                                }

                                $warna = match($status) {
                                    'selesai' => $validasi ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                                    'proses', 'dikerjakan' => 'bg-blue-100 text-blue-800 border border-blue-300',
                                    'menunggu', 'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                                    default => 'bg-gray-100 text-gray-800 border border-gray-300',
                                };
                            @endphp

                                    <span class="px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap {{ $warna }}">
                            {{ $statusTampil }}
                        </span>
                        </td>

                        {{-- Kolom Foto Bukti --}}
                        <td class="p-2 border text-center">
                            @if (!empty($t['bukti_foto']))
                                <a href="{{ asset('storage/' . $t['bukti_foto']) }}" target="_blank" class="text-gray-700 hover:text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center py-4 text-gray-500 italic">
                            Tidak ada data riwayat tugas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
