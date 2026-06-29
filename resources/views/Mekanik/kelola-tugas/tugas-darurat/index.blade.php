@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-lg">

    <!-- Judul -->
    <h2 class="text-lg sm:text-2xl font-bold text-gray-700 mb-4 sm:mb-6">
        Tugas Darurat Anda
    </h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-sm border border-green-200 text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300">
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden text-[10px] sm:text-sm">

            <thead class="bg-rose-100 text-gray-700 uppercase text-[10px] sm:text-xs tracking-wide">
                <tr class="text-center">
                    <th class="p-2 sm:p-3 border-b">No</th>
                    <th class="p-2 sm:p-3 border-b">Pemberi Tugas</th>
                    <th class="p-2 sm:p-3 border-b">Tgl Mulai</th>
                    <th class="p-2 sm:p-3 border-b">Tgl Selesai</th>
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
                @forelse($tugas as $tugas)
                    @php
                        $fotoExists = $tugas->bukti_foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($tugas->bukti_foto);

                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'release_order' => 'bg-yellow-100 text-yellow-700',
                            'dikerjakan' => 'bg-blue-100 text-blue-700',
                            'selesai' => 'bg-green-100 text-green-700',
                        ];

                        $nextStatus = [];
                        if($tugas->status == 'pending' || $tugas->status == 'release_order') {
                            $nextStatus = ['dikerjakan' => 'Dikerjakan'];
                        } elseif($tugas->status == 'dikerjakan') {
                            $nextStatus = ['selesai' => 'Selesai'];
                        }
                    @endphp

                    <tr class="text-center hover:bg-gray-50 transition">

                        <td class="p-1 sm:p-2 border-b">{{ $loop->iteration }}</td>

                        <td class="p-1 sm:p-2 border-b font-medium text-gray-700">
                            {{ $tugas->pemberi_tugas }}
                        </td>

                        <td class="p-1 sm:p-2 border-b whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($tugas->tgl_mulai)->format('Y-m-d') }}
                        </td>

                        <td class="p-1 sm:p-2 border-b whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($tugas->tgl_selesai)->format('Y-m-d') }}
                        </td>

                        <td class="p-1 sm:p-2 border-b">
                            {{ $tugas->nama_mekanik }}
                        </td>

                        <td class="p-1 sm:p-2 border-b break-words max-w-[120px] sm:max-w-none">
                            {{ $tugas->equipment }}
                        </td>

                        <td class="p-1 sm:p-2 border-b">
                            {{ $tugas->tag_number }}
                        </td>

                        <td class="p-1 sm:p-2 border-b">
                            {{ $tugas->eq_class }}
                        </td>

                        <td class="p-1 sm:p-2 border-b">
                            {{ $tugas->bom ?? '-' }}
                        </td>

                        <td class="p-1 sm:p-2 border-b text-left max-w-[140px] sm:max-w-none truncate">
                            {{ $tugas->task_list }}
                        </td>

                        <td class="p-1 sm:p-2 border-b break-words max-w-[120px] sm:max-w-none">
                            {{ $tugas->lokasi }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-1 sm:p-2 border-b">
                            @if(!empty($nextStatus))
                                <form action="{{ route('mekanik.tugas-darurat.update-status', $tugas->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                        class="w-full px-1 sm:px-2 py-1 rounded-lg text-[10px] sm:text-sm font-semibold {{ $statusClasses[$tugas->status] ?? '' }}">

                                        <option value="{{ $tugas->status }}" selected>
                                            {{ $tugas->status=='pending' || $tugas->status=='release_order' ? 'Release Order' : ($tugas->status=='dikerjakan'?'Dikerjakan':'Selesai') }}
                                        </option>

                                        @foreach($nextStatus as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <span class="block w-full px-1 sm:px-2 py-1 rounded-lg text-[10px] sm:text-sm font-semibold {{ $statusClasses[$tugas->status] ?? '' }}">
                                    {{ $tugas->status=='pending' || $tugas->status=='release_order' ? 'Release Order' : ($tugas->status=='dikerjakan'?'Dikerjakan':'Selesai') }}
                                </span>
                            @endif
                        </td>

                       <!-- FOTO -->
<td class="p-1 sm:p-2 border-b">
    <div class="flex items-center justify-center gap-3">

        <!-- LIHAT FOTO -->
        @if($fotoExists)
            <a href="/storage/{{ $tugas->bukti_foto }}" target="_blank"
               class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2"
                     stroke="currentColor"
                     class="w-5 h-5 text-blue-600">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                </svg>
            </a>
        @endif

        <!-- FORM UPLOAD -->
        <form action="{{ route('mekanik.tugas-darurat.upload', $tugas->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="flex items-center gap-2">
            @csrf

            <input type="file"
                   name="bukti_foto"
                   class="hidden"
                   id="uploadFoto{{ $tugas->id }}">

            <!-- BUTTON PILIH FOTO -->
            <label for="uploadFoto{{ $tugas->id }}"
                   class="cursor-pointer p-2 rounded-lg bg-rose-100 hover:bg-rose-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2"
                     stroke="currentColor"
                     class="w-5 h-5 text-rose-600">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 16.5V3m0 0l-3.75 3.75M12 3l3.75 3.75M3 15.75v2.25A2.25 2.25 0 005.25 20.25h13.5A2.25 2.25 0 0021 18v-2.25" />
                </svg>
            </label>

            <!-- BUTTON SIMPAN -->
            <button type="submit"
                    class="p-2 rounded-lg bg-green-100 hover:bg-green-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2"
                     stroke="currentColor"
                     class="w-5 h-5 text-green-600">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </button>

        </form>

    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="p-4 text-center text-gray-500 italic">
                            Belum ada tugas darurat.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection
