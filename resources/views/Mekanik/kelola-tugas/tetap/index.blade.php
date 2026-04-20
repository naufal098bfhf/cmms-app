@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-lg sm:text-2xl font-bold text-gray-700 mb-4 sm:mb-6">Tugas Tetap Anda</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300">
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden text-[10px] sm:text-sm">
            <thead class="bg-rose-100 text-gray-700 uppercase text-xs tracking-wide">
                <tr class="text-center">
                   <th class="p-2 sm:p-3 border-b">No</th>
                    <th class="p-2 sm:p-3 border-b">Pemberi Tugas</th>
                    <th class="p-2 sm:p-3 border-b">Jenis Tugas</th>
                    <th class="p-2 sm:p-3 border-b">Tanggal Jadwal</th>
                    <th class="p-2 sm:p-3 border-b">Nama Mekanik</th>
                    <th class="p-2 sm:p-3 border-b">Equipment</th>
                    <th class="p-2 sm:p-3 border-b">Tag Number</th>
                    <th class="p-2 sm:p-3 border-b">Eq. Class</th>
                    <th class="p-2 sm:p-3 border-b">BoM</th>
                   <th class="p-2 sm:p-3 border-b">Task List</th>
                    <th class="p-2 sm:p-3 border-b">Lokasi</th>
                    <th class="p-2 sm:p-3 border-b">Status</th>
                    <th class="p-2 sm:p-3 border-b">Bukti Foto</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                @forelse($tugasTetap as $tugas)
                    @php
                        $fotoExists = $tugas->bukti_foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($tugas->bukti_foto);

                        // Tentukan opsi status berikutnya
                        $nextStatus = [];
                        if ($tugas->status == 'pending') {
                            $nextStatus = ['dikerjakan' => 'Dikerjakan'];
                        } elseif ($tugas->status == 'dikerjakan') {
                            $nextStatus = ['selesai' => 'Selesai'];
                        }

                        // Warna status
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'dikerjakan' => 'bg-blue-100 text-blue-700',
                            'selesai' => 'bg-green-100 text-green-700',
                        ];

                        // Label status tampilan
                        $statusLabel = [
                            'pending' => 'Release Order',
                            'dikerjakan' => 'Dikerjakan',
                            'selesai' => 'Selesai',
                        ];
                    @endphp

                    <tr class="text-center hover:bg-gray-50 transition">
                       <td class="p-1 sm:p-2 border-b">{{ $loop->iteration }}</td>
                        <td class="p-2 border-b font-medium text-gray-700">{{ $tugas->pemberi_tugas }}</td>
                       <td class="p-1 sm:p-2 border-b">{{ ucfirst($tugas->jenis_tugas) }}</td>
                       <td class="p-1 sm:p-2 border-b">
                            @if($tugas->jenis_tugas === 'mingguan') {{ $tugas->hari_mingguan }}
                            @elseif($tugas->jenis_tugas === 'bulanan') {{ $tugas->tanggal_bulanan }}
                            @elseif($tugas->jenis_tugas === 'tahunan') {{ $tugas->tanggal_tahunan }}
                            @else - @endif
                        </td>
                        <td class="p-1 sm:p-2 border-b">{{ $tugas->nama_mekanik }}</td>
                        <td class="p-1 sm:p-2 border-b">{{ $tugas->equipment }}</td>
                       <td class="p-1 sm:p-2 border-b">{{ $tugas->tag_number }}</td>
                        <td class="p-1 sm:p-2 border-b">{{ $tugas->eq_class }}</td>
                        <td class="p-1 sm:p-2 border-b">{{ $tugas->bom ?? '-' }}</td>
                       <td class="p-1 sm:p-2 border-b text-left max-w-[120px] sm:max-w-none truncate">{{ $tugas->task_list }}</td>
                      <td class="p-1 sm:p-2 border-b">{{ $tugas->lokasi }}</td>

                        <!-- STATUS -->
                       <td class="p-1 sm:p-2 border-b">
                            @if(!empty($nextStatus))
                                <form action="{{ route('mekanik.tugas-tetap.update-status', $tugas->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                        class="w-full px-1 sm:px-2 py-1 text-[10px] sm:text-sm font-semibold {{ $statusClasses[$tugas->status] ?? '' }}">
                                        <option value="{{ $tugas->status }}" selected>
                                            {{ $statusLabel[$tugas->status] ?? ucfirst($tugas->status) }}
                                        </option>
                                        @foreach($nextStatus as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <span class="block w-full px-2 py-1 rounded-lg text-sm font-semibold {{ $statusClasses[$tugas->status] ?? '' }}">
                                    {{ $statusLabel[$tugas->status] ?? ucfirst($tugas->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- BUKTI FOTO -->
                        <td class="p-1 sm:p-2 border-b">
                            <div class="flex items-center gap-3 justify-center">

                                {{-- Lihat Foto --}}
                                @if($fotoExists)
                                    <a href="{{ asset('storage/' . $tugas->bukti_foto) }}?t={{ filemtime(storage_path('app/public/' . $tugas->bukti_foto)) }}"
                                       target="_blank"
                                       class="text-blue-500 hover:text-blue-600 transition"
                                       title="Lihat Foto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-gray-400 flex items-center justify-center" title="Belum ada foto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.365-3.682m3.67-2.361A9.962 9.962 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.958 9.958 0 01-1.657 2.774M15 12a3 3 0 11-6 0 3 3 0z"/>
                                        </svg>
                                    </span>
                                @endif

                                {{-- Upload/Ganti Foto --}}
                                <form action="{{ route('mekanik.tugas-tetap.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3 m-0 p-0">
                                    @csrf
                                    <input type="file" name="bukti_foto" accept="image/*" class="hidden" id="uploadFoto{{ $tugas->id }}">
                                    <label for="uploadFoto{{ $tugas->id }}" class="text-rose-500 hover:text-rose-600 transition cursor-pointer" title="Upload/Ganti Foto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v8m0-8l-3-3m3 3l3-3M12 4v8"/>
                                        </svg>
                                    </label>
                                    <button type="submit" class="text-green-500 hover:text-green-600 transition" title="Simpan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="p-4 text-center text-gray-500 italic">
                            Belum ada tugas tetap.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
