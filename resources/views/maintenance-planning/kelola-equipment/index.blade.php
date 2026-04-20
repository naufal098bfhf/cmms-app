@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-4 md:mb-6">Kelola Data Equipment</h2>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('maintenance-planning.kelola-equipment.index') }}" class="flex items-center gap-4 mb-6">
        <input type="text" name="search" placeholder="Cari Nama/Kode Mesin"
            class="border rounded-lg px-3 py-2 w-full md:w-1/3" value="{{ request('search') }}">

        <select name="filter" class="border rounded-lg px-3 py-2">
            <option value="">Semua</option>
            <option value="baik" {{ request('filter') == 'baik' ? 'selected' : '' }}>Baik</option>
            <option value="rusak" {{ request('filter') == 'rusak' ? 'selected' : '' }}>Rusak</option>
        </select>

        <button type="submit" class="bg-red-300 px-4 py-2 rounded-lg">Cari</button>

        <a href="{{ route('maintenance-planning.kelola-equipment.create') }}"
           class="w-full md:w-auto md:ml-auto bg-red-400 hover:bg-red-500 text-white px-4 py-2 rounded-lg text-center">
            Tambah Baru
        </a>
    </form>

    <!-- Tabel Data -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px] border border-gray-200 rounded-lg overflow-hidden shadow-sm text-xs md:text-sm">
            <thead class="bg-red-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Equipment</th>
                    <th class="px-4 py-2 border">Tag Number</th>
                    <th class="px-4 py-2 border">Tanggal Masuk Aset</th>
                    <th class="px-4 py-2 border">Kondisi</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment as $item)
                <tr class="text-center border-b hover:bg-gray-50 transition">
                    <td class="px-4 py-2">{{ $loop->iteration + ($equipment->currentPage() - 1) * $equipment->perPage() }}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">{{ $item->name }}</td>
                    <td class="px-4 py-2">{{ $item->tag_number }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal_masuk_aset)->translatedFormat('d F Y') }}</td>
                    <td class="px-4 py-2">
                        @if($item->kondisi === 'baik')
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">
                                Baik
                            </span>
                        @elseif($item->kondisi === 'rusak')
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-medium">
                                Rusak
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                                Tidak diketahui
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                 <td class="px-4 py-3 text-center">
    <div class="flex justify-center items-center gap-3">

        {{-- Edit --}}
        <a href="{{ route('maintenance-planning.kelola-equipment.edit', $item->id) }}"
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
        <form method="POST" action="{{ route('maintenance-planning.kelola-equipment.destroy', $item->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Yakin hapus equipment ini?')"
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

    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500 italic">Tidak ada data equipment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $equipment->links() }}
    </div>
</div>
@endsection
