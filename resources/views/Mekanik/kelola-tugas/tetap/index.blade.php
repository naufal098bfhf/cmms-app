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
<!-- BUKTI FOTO -->
<td class="p-3 border-b">
    <div class="flex items-center justify-center gap-2">

        {{-- STATUS --}}
        @if($fotoExists)
            <div class="flex items-center gap-1 px-2 py-1
                        rounded-full bg-emerald-100 text-emerald-700
                        text-[11px] font-semibold shadow-sm whitespace-nowrap">

                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Uploaded
            </div>
        @else
            <div class="flex items-center gap-1 px-2 py-1
                        rounded-full bg-rose-100 text-rose-700
                        text-[11px] font-semibold shadow-sm whitespace-nowrap">

                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                Belum
            </div>
        @endif

        {{-- LIHAT FOTO --}}
        @if($fotoExists)
            <a href="/storage/{{ $tugas->bukti_foto }}"
               target="_blank"
               title="Lihat Foto"
               class="flex items-center justify-center
                      w-9 h-9 rounded-xl
                      bg-blue-100 hover:bg-blue-200
                      text-blue-600 shadow-sm
                      transition-all duration-200 hover:scale-105">

                <i class="fa-solid fa-eye"></i>
            </a>
        @endif

        {{-- FORM --}}
        <form action="{{ route('mekanik.tugas-tetap.upload', $tugas->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="flex items-center gap-2">
            @csrf

            <input type="file"
                   name="bukti_foto"
                   accept="image/*"
                   class="hidden"
                   id="uploadFoto{{ $tugas->id }}">

            {{-- UPLOAD --}}
            <label for="uploadFoto{{ $tugas->id }}"
                   title="Upload Foto"
                   class="flex items-center justify-center
                          w-9 h-9 rounded-xl
                          bg-amber-100 hover:bg-amber-200
                          text-amber-600 shadow-sm
                          cursor-pointer transition-all duration-200 hover:scale-105">

                <i class="fa-solid fa-upload"></i>
            </label>

            {{-- SIMPAN --}}
            <button type="submit"
                    title="Simpan"
                    class="flex items-center justify-center
                           w-9 h-9 rounded-xl
                           bg-emerald-100 hover:bg-emerald-200
                           text-emerald-600 shadow-sm
                           transition-all duration-200 hover:scale-105">

                <i class="fa-solid fa-check"></i>
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
