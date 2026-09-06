@extends('layouts.app')

@section('content')

<div class="w-full px-3 sm:px-4 lg:px-6 py-4 sm:py-6">

{{-- CARD UTAMA --}}
<div class="bg-white rounded-xl sm:rounded-2xl shadow-md overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 sm:px-6 py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

            <div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-gray-800">
                    Riwayat Tugas
                </h2>

                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Daftar riwayat tugas yang telah dibuat dan dikerjakan
                </p>
            </div>

        </div>
    </div>


    {{-- FILTER --}}
    <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gray-50 border-b border-gray-200">

        <form
            method="GET"
            action="{{ route('admin.riwayat-tugas.index') }}"
            class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end gap-3"
        >

            {{-- TANGGAL MULAI --}}
            <div class="w-full lg:w-auto">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full lg:w-auto border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-rose-400
                           focus:border-rose-400"
                >
            </div>


            {{-- TANGGAL AKHIR --}}
            <div class="w-full lg:w-auto">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Tanggal Akhir
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full lg:w-auto border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-rose-400
                           focus:border-rose-400"
                >
            </div>


            {{-- PENCARIAN --}}
            <div class="w-full lg:flex-1 lg:min-w-[250px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Pencarian
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, kode, atau equipment..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-rose-400
                           focus:border-rose-400"
                >
            </div>


            {{-- TOMBOL FILTER --}}
            <div class="w-full sm:w-auto">
                <button
                    type="submit"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                           bg-rose-500 hover:bg-rose-600 active:bg-rose-700
                           text-white font-medium px-5 py-2.5 rounded-lg text-sm
                           transition duration-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707L15 12v6.586a1 1 0 01-.445.832l-4 2.5A1 1 0 019 21.086V12L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>

                    Filter
                </button>
            </div>


            {{-- DOWNLOAD PDF --}}
            <div class="w-full sm:w-auto">
                <a
                    href="{{ route('admin.riwayat-tugas.pdf', request()->query()) }}"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                           bg-red-600 hover:bg-red-700 active:bg-red-800
                           text-white font-medium px-5 py-2.5 rounded-lg text-sm
                           transition duration-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>

                    Download PDF
                </a>
            </div>

        </form>
    </div>


    {{-- INFORMASI MOBILE --}}
    <div class="block sm:hidden px-4 py-3 bg-rose-50 border-b border-rose-100">
        <div class="flex items-center gap-2 text-xs text-rose-700">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                />
            </svg>

            <span>
                Geser tabel ke kiri atau kanan untuk melihat semua data.
            </span>
        </div>
    </div>


    {{-- TABEL --}}
    <div class="w-full overflow-x-auto">

        <table class="w-full min-w-[1500px] text-xs sm:text-sm border-collapse">

            {{-- HEADER --}}
            <thead class="bg-rose-100 text-gray-800">

                <tr class="text-center">

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        No
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Pemberi Tugas
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Jenis
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Tanggal
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Mekanik
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Equipment
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Tag
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        EQ Class
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        BoM
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Task
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Lokasi
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Status
                    </th>

                    <th class="px-3 py-3 border border-rose-200 font-semibold whitespace-nowrap">
                        Foto Bukti
                    </th>

                </tr>

            </thead>


            {{-- BODY --}}
            <tbody class="bg-white">

                @forelse ($riwayat as $index => $t)

                    @php

                        $status = strtolower($t['status'] ?? '');

                        $validasi =
                            isset($t['validasi_mp']) &&
                            (
                                $t['validasi_mp'] == 1 ||
                                $t['validasi_mp'] === true ||
                                $t['validasi_mp'] === '1'
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS YANG DITAMPILKAN
                        |--------------------------------------------------------------------------
                        */

                        if ($status === 'pending') {

                            $statusTampil = 'Release Order';

                        } elseif ($status === 'dikerjakan') {

                            $statusTampil = 'Dikerjakan';

                        } elseif ($status === 'selesai' && !$validasi) {

                            $statusTampil = 'Menunggu Validasi MP';

                        } else {

                            $statusTampil = 'Selesai';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | WARNA STATUS
                        |--------------------------------------------------------------------------
                        */

                        $warna = match ($status) {

                            'selesai' =>
                                $validasi
                                    ? 'bg-green-100 text-green-800 border-green-300'
                                    : 'bg-yellow-100 text-yellow-800 border-yellow-300',

                            'proses',
                            'dikerjakan' =>
                                'bg-blue-100 text-blue-800 border-blue-300',

                            'menunggu',
                            'pending' =>
                                'bg-yellow-100 text-yellow-800 border-yellow-300',

                            default =>
                                'bg-gray-100 text-gray-800 border-gray-300',

                        };

                    @endphp


                    <tr
                        class="
                            text-center
                            border-b
                            border-gray-200
                            hover:bg-rose-50
                            transition-colors
                            duration-150
                        "
                    >

                        {{-- NO --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap">
                            {{ $index + 1 }}
                        </td>


                        {{-- PEMBERI TUGAS --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap">
                            {{ $t['pemberi_tugas'] ?? '-' }}
                        </td>


                        {{-- JENIS --}}
                        <td class="px-3 py-3 border border-gray-200 font-medium whitespace-nowrap">
                            {{ $t['jenis'] ?? '-' }}
                        </td>


                        {{-- TANGGAL --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap">
                            {{ $t['tgl_mulai'] ?? '-' }}
                        </td>


                        {{-- MEKANIK --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap">
                            {{ $t['nama_mekanik'] ?? '-' }}
                        </td>


                        {{-- EQUIPMENT --}}
                        <td
                            class="
                                px-3 py-3
                                border border-gray-200
                                text-left
                                max-w-[220px]
                            "
                        >
                            <div
                                class="truncate"
                                title="{{ $t['equipment'] ?? '-' }}"
                            >
                                {{ $t['equipment'] ?? '-' }}
                            </div>
                        </td>


                        {{-- TAG --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap font-medium">
                            {{ $t['tag_number'] ?? '-' }}
                        </td>


                        {{-- EQ CLASS --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap">
                            {{ $t['eq_class'] ?? '-' }}
                        </td>


                        {{-- BOM --}}
                        <td class="px-3 py-3 border border-gray-200 whitespace-nowrap">
                            {{ $t['bom'] ?? '-' }}
                        </td>


                        {{-- TASK --}}
                        <td
                            class="
                                px-3 py-3
                                border border-gray-200
                                text-left
                                max-w-[300px]
                            "
                        >
                            <div
                                class="truncate"
                                title="{{ $t['task_list'] ?? '-' }}"
                            >
                                {{ $t['task_list'] ?? '-' }}
                            </div>
                        </td>


                        {{-- LOKASI --}}
                        <td
                            class="
                                px-3 py-3
                                border border-gray-200
                                whitespace-nowrap
                            "
                        >
                            {{ $t['lokasi'] ?? '-' }}
                        </td>


                        {{-- STATUS --}}
                        <td class="px-3 py-3 border border-gray-200 text-center">

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    rounded-full
                                    border
                                    whitespace-nowrap
                                    {{ $warna }}
                                "
                            >
                                {{ $statusTampil }}
                            </span>

                        </td>


                        {{-- FOTO BUKTI --}}
                        <td class="px-3 py-3 border border-gray-200 text-center">

                            @if (!empty($t['bukti_foto']))

                                <a
                                    href="{{ asset('storage/' . $t['bukti_foto']) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="
                                        inline-flex
                                        items-center
                                        justify-center
                                        w-9
                                        h-9
                                        rounded-lg
                                        bg-gray-100
                                        text-gray-600
                                        hover:bg-gray-200
                                        hover:text-gray-900
                                        transition
                                    "
                                    title="Lihat Foto Bukti"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>

                                </a>

                            @else

                                <span class="text-gray-400 italic">
                                    -
                                </span>

                            @endif

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="13"
                            class="
                                px-4
                                py-10
                                text-center
                                text-gray-500
                                italic
                                border
                                border-gray-200
                            "
                        >

                            <div class="flex flex-col items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-10 h-10 text-gray-300 mb-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>

                                <span>
                                    Tidak ada data riwayat tugas.
                                </span>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- FOOTER / INFO --}}
    @if (count($riwayat) > 0)

        <div
            class="
                px-4
                sm:px-6
                py-3
                bg-gray-50
                border-t
                border-gray-200
                text-xs
                sm:text-sm
                text-gray-500
            "
        >
            Menampilkan
            <span class="font-semibold text-gray-700">
                {{ count($riwayat) }}
            </span>
            data riwayat tugas.
        </div>

    @endif

</div>


</div>

@endsection
