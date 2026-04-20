@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6 md:p-8">

    {{-- Header --}}
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-2">
        Edit Data Equipment
    </h2>
    <p class="text-sm text-gray-500 mb-6">
        Perbarui informasi equipment yang sudah terdaftar
    </p>

    <form action="{{ route('maintenance-planning.kelola-equipment.update', $equipment->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ================= INFORMASI EQUIPMENT ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Informasi Equipment
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Nama Equipment --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Equipment
            </label>
            <input type="text" name="name"
                value="{{ old('name', $equipment->name) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        </div>

        {{-- Tag Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tag Number
            </label>
            <input type="text" name="tag_number"
                value="{{ old('tag_number', $equipment->tag_number) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
            @error('tag_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= DETAIL ASET ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Detail Aset
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tanggal Masuk Aset --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Masuk Aset
            </label>
            <input type="date" name="tanggal_masuk_aset"
                value="{{ old('tanggal_masuk_aset', $equipment->tanggal_masuk_aset) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
            @error('tanggal_masuk_aset')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kondisi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kondisi Equipment
            </label>
            <select name="kondisi"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
                <option value="" disabled> Pilih Kondisi </option>
                <option value="baik"
                    {{ old('kondisi', $equipment->kondisi) == 'baik' ? 'selected' : '' }}>
                    Baik
                </option>
                <option value="rusak"
                    {{ old('kondisi', $equipment->kondisi) == 'rusak' ? 'selected' : '' }}>
                    Rusak
                </option>
            </select>
            @error('kondisi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        </div>
        {{-- ================= AKSI ================= --}}
        <div class="flex flex-col-reverse md:flex-row gap-3 md:justify-between pt-6 border-t mt-8">
            <a href="{{ route('maintenance-planning.kelola-equipment.index') }}"
               <a class="w-full md:w-auto text-center px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700
                      hover:bg-gray-200 transition">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-red-600 text-white
                       font-semibold hover:bg-red-700 transition shadow-md">
                Update Equipment
            </button>
        </div>

    </form>
</div>
@endsection
