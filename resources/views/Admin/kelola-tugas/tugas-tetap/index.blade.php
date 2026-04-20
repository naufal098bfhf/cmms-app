@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-700 mb-6">Kelola Tugas Tetap</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Status -->
    <form method="GET" action="{{ route('admin.kelola-tugas.tugas-tetap.index') }}" class="flex items-center gap-4 mb-6">
        <select name="status" class="border p-2 rounded-lg">
            <option value="semua">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Release Order</option>
            <option value="dikerjakan" {{ request('status') === 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit" class="bg-rose-300 text-white px-4 py-2 rounded-lg hover:bg-rose-400">Filter</button>
        <a href="{{ route('admin.kelola-tugas.tugas-tetap.create') }}" class="ml-auto bg-rose-300 text-white px-4 py-2 rounded-lg hover:bg-rose-400">+ Tambah Tugas</a>
    </form>

    <!-- Tabel Data -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Pemberi Tugas</th>
                    <th class="px-4 py-2 border">Jenis Tugas</th>
                    <th class="px-4 py-2 border">Tanggal Jadwal</th>
                    <th class="px-4 py-2 border">Nama Mekanik</th>
                    <th class="px-4 py-2 border">Equipment</th>
                    <th class="px-4 py-2 border">Tag Number</th>
                    <th class="px-4 py-2 border">EQ Class</th>
                    <th class="px-4 py-2 border">BoM</th>
                    <th class="px-4 py-2 border">Task List</th>
                    <th class="px-4 py-2 border">Lokasi</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugasTetap as $tugas)
                    <tr class="text-center">
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->pemberi_tugas }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->jenis_tugas }}</td>
                        <!-- Tanggal Jadwal -->
                        <td class="px-4 py-2 border">
                            @if($tugas->jenis_tugas === 'mingguan')
                                {{ $tugas->hari_mingguan }}
                            @elseif($tugas->jenis_tugas === 'bulanan')
                                {{ $tugas->tanggal_bulanan }}
                            @elseif($tugas->jenis_tugas === 'tahunan')
                                {{ $tugas->tanggal_tahunan }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2 border">{{ $tugas->nama_mekanik }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->equipment }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->tag_number }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->eq_class }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->bom ?? '-' }}</td>
                        <td class="px-4 py-2 border text-left">{{ $tugas->task_list }}</td>
                        <td class="px-4 py-2 border">{{ $tugas->lokasi }}</td>

                        <!-- Status -->
                        <td class="px-4 py-2 border">
                            @php
                                $statusColors = [
                                    'pending'       => 'bg-yellow-500',
                                    'dikerjakan'    => 'bg-blue-500',
                                    'selesai'       => 'bg-green-600',
                                ];
                                $statusLabels = [
                                    'pending'       => 'Release Order',
                                    'dikerjakan'    => 'Dikerjakan',
                                    'selesai'       => 'Selesai',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-lg text-white {{ $statusColors[$tugas->status] ?? 'bg-gray-400' }}">
                                {{ $statusLabels[$tugas->status] ?? ucfirst($tugas->status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 flex justify-center gap-3">
                            {{-- Edit --}}
                            <a href="{{ route('admin.kelola-tugas.tugas-tetap.edit', $tugas->id) }}"
                               class="text-blue-500 hover:text-blue-600 transition"
                               title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.121
                                              2.121 0 013 3L12 14H9v-3z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 13v6a2 2 0 01-2 2H7a2 2 0
                                              01-2-2V7a2 2 0 012-2h6"/>
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.kelola-tugas.tugas-tetap.destroy', $tugas->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin hapus tugas ini?')"
                                        class="text-red-500 hover:text-red-600 transition"
                                        title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 7l-.867 12.142A2 2 0
                                                  0116.138 21H7.862a2 2 0
                                                  01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0
                                                  011 1v2H9V4a1 1 0 011-1z"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="px-4 py-4 text-center text-gray-500">Belum ada data tugas tetap.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
