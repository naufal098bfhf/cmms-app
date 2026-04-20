@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-700 mb-6">Kelola Tugas Darurat</h2>

    {{-- Form pencarian --}}
    <form method="GET" action="{{ route('admin.kelola-tugas.tugas-darurat.index') }}" class="flex flex-wrap gap-3 mb-5">
        <input type="text" name="nama" placeholder="Cari Nama" value="{{ request('nama') }}"
               class="border border-gray-300 rounded-lg px-3 py-2">
        <button type="submit" class="bg-rose-300 text-white px-4 py-2 rounded-lg hover:bg-rose-400">Cari</button>
        <a href="{{ route('admin.kelola-tugas.tugas-darurat.create') }}" class="bg-rose-300 text-white px-4 py-2 rounded-lg hover:bg-rose-400 ml-auto">Tambah Baru</a>
    </form>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 text-sm text-left">
            <thead class="bg-rose-100 text-gray-700">
                <tr>
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Pemberi Tugas</th>
                    <th class="p-2 border">Tgl Mulai</th>
                    <th class="p-2 border">Tgl Selesai</th>
                    <th class="p-2 border">Nama Mekanik</th>
                    <th class="p-2 border">Equipment</th>
                    <th class="p-2 border">Tag Number</th>
                    <th class="p-2 border">Eq. Class</th>
                    <th class="p-2 border">BoM</th>
                    <th class="p-2 border">Task List</th>
                    <th class="p-2 border">Lokasi</th>
                    <th class="p-2 border text-center">Status</th>
                    <th class="p-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugasDarurat as $i => $tugas)
                    <tr class="hover:bg-rose-50">
                        <td class="p-2 border text-center">{{ $i + 1 }}</td>
                        <td class="p-2 border">{{ $tugas->pemberi_tugas }}</td>
                        <td class="p-2 border">
                            {{ \Carbon\Carbon::parse($tugas->tgl_mulai)->format('Y-m-d') }}
                        </td>
                        <td class="p-2 border">
                            {{ \Carbon\Carbon::parse($tugas->tgl_selesai)->format('Y-m-d') }}
                        </td>

                        <td class="p-2 border">{{ $tugas->nama_mekanik }}</td>
                        <td class="p-2 border">{{ $tugas->equipment }}</td>
                        <td class="p-2 border">{{ $tugas->tag_number }}</td>

                        {{-- ✅ Perbaikan utama: pastikan eq_class terbaca aman meskipun null --}}
                        <td class="p-2 border">{{ $tugas->eq_class ?? '-' }}</td>

                        <td class="p-2 border">{{ $tugas->bom }}</td>
                        <td class="p-2 border">{{ $tugas->task_list }}</td>
                        <td class="p-2 border">{{ $tugas->lokasi }}</td>
<td class="p-2 border text-center">
    @if($tugas->status === 'pending')
        <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">
            Release Order
        </span>
    @elseif($tugas->status === 'dikerjakan')
        <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
            Dikerjakan
        </span>
    @elseif($tugas->status === 'selesai')
        <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs font-semibold">
            Selesai
        </span>
    @else
        <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-xs">
            -
        </span>
    @endif
</td>

                        <td class="px-4 py-3 flex justify-center gap-3">
                            {{-- Edit --}}
                            <a href="{{ route('admin.kelola-tugas.tugas-darurat.edit', $tugas->id) }}"
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
                            <form method="POST" action="{{ route('admin.kelola-tugas.tugas-darurat.destroy', $tugas->id) }}">
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
                        <td colspan="13" class="p-3 text-center text-gray-500">Belum ada data tugas darurat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
