@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
        Edit Data Equipment
    </h2>
    <p class="text-sm text-gray-500 mb-6">
        Perbarui informasi aset equipment
    </p>

    <form action="{{ route('admin.kelola-equipment.update', $equipment->id) }}"
          method="POST"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ================= INFORMASI EQUIPMENT ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Informasi Equipment
        </h3>

        {{-- Nama Equipment --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Equipment
            </label>
            <input type="text" name="name"
                value="{{ old('name', $equipment->name) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tag Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tag Number
            </label>
            <input type="text" name="tag_number"
                value="{{ old('tag_number', $equipment->tag_number) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
            @error('tag_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Masuk Aset --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Masuk Aset
            </label>
            <input type="date" name="tanggal_masuk_aset"
                value="{{ old('tanggal_masuk_aset', $equipment->tanggal_masuk_aset) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
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
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
                <option value="" disabled>Pilih Kondisi</option>
                <option value="baik" {{ old('kondisi', $equipment->kondisi) == 'baik' ? 'selected' : '' }}>
                    Baik
                </option>
                <option value="rusak" {{ old('kondisi', $equipment->kondisi) == 'rusak' ? 'selected' : '' }}>
                    Rusak
                </option>
            </select>
            @error('kondisi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-between pt-6 border-t mt-8">
            <a href="{{ route('admin.kelola-equipment.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700
                      hover:bg-gray-200 transition">
                Kembali
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-red-600 text-white
                       font-semibold hover:bg-red-700 transition shadow-md">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection
