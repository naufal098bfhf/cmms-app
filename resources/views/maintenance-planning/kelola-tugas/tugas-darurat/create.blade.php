@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    {{-- Header --}}
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
        Tambah Tugas Darurat
    </h2>
    <p class="text-sm text-gray-500 mb-6">
        Form pembuatan tugas maintenance darurat
    </p>

    <form action="{{ route('maintenance-planning.kelola-tugas.tugas-darurat.store') }}"
          method="POST"
          class="space-y-6">
        @csrf

        {{-- ================= INFORMASI TUGAS ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Informasi Tugas
        </h3>

        {{-- Pemberi Tugas --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pemberi Tugas</label>
            <input type="text" name="pemberi_tugas"
                   value="{{ Auth::user()->name }}" readonly
                   class="w-full rounded-lg border border-gray-300 bg-gray-100
                          px-4 py-2.5">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tanggal Mulai --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai') }}"
                       class="w-full rounded-lg border border-gray-300 bg-gray-50
                              focus:bg-white focus:border-red-500
                              focus:ring focus:ring-red-200
                              px-4 py-2.5" required>
            </div>

            {{-- Tanggal Selesai --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}"
                       class="w-full rounded-lg border border-gray-300 bg-gray-50
                              focus:bg-white focus:border-red-500
                              focus:ring focus:ring-red-200
                              px-4 py-2.5" required>
            </div>
        </div>

        {{-- ================= SUMBER DAYA ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700">
            Mekanik & Equipment
        </h3>

        {{-- Mekanik --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mekanik</label>
            <select name="mekanik_id[]" id="mekanik_id" multiple required
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2">
                @foreach($mekanik as $m)
                    <option value="{{ $m->id }}"
                        {{ collect(old('mekanik_id'))->contains($m->id) ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">
                Pilih satu atau lebih mekanik
            </p>
        </div>

        {{-- Equipment --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
            <select name="equipment_id" id="equipment_select" required
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5">
                <option value="">Pilih Equipment</option>
                @foreach($equipment as $eq)
                    <option value="{{ $eq->id }}"
                            data-tag="{{ $eq->tag_number }}"
                            data-eqclass="{{ $eq->eq_class }}">
                        {{ $eq->name }} ({{ $eq->tag_number }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tag Number --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tag Number</label>
                <input type="text" name="tag_number" id="tag_number" readonly
                       class="w-full rounded-lg border border-gray-300 bg-gray-100
                              px-4 py-2.5">
            </div>

            {{-- EQ Class --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">EQ Class</label>
                <input type="text" name="eq_class" id="eq_class" readonly
                       class="w-full rounded-lg border border-gray-300 bg-gray-100
                              px-4 py-2.5">
            </div>
        </div>

        {{-- ================= DETAIL PEKERJAAN ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700">
            Detail Pekerjaan
        </h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">BoM</label>
            <input type="text" name="bom" value="{{ old('bom') }}"
                   class="w-full rounded-lg border border-gray-300 bg-gray-50
                          focus:bg-white focus:border-red-500
                          focus:ring focus:ring-red-200
                          px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Task List</label>
            <textarea name="task_list" rows="3"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5">{{ old('task_list') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                   class="w-full rounded-lg border border-gray-300 bg-gray-50
                          focus:bg-white focus:border-red-500
                          focus:ring focus:ring-red-200
                          px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500
                       focus:ring focus:ring-red-200
                       px-4 py-2.5">
                <option value="release_order">Release Order</option>
                <option value="dikerjakan">Dikerjakan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-between pt-6 border-t mt-8">
            <a href="{{ route('maintenance-planning.kelola-tugas.tugas-darurat.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700
                      hover:bg-gray-200 transition">
                Kembali
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-red-600 text-white
                       font-semibold hover:bg-red-700 transition shadow-md">
                Simpan Tugas
            </button>
        </div>

    </form>
</div>

{{-- Tom Select --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const equipmentSelect = document.getElementById('equipment_select');
    const tagInput = document.getElementById('tag_number');
    const eqClassInput = document.getElementById('eq_class');

    equipmentSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        tagInput.value = opt.getAttribute('data-tag') || '';
        eqClassInput.value = opt.getAttribute('data-eqclass') || '';
    });

    new TomSelect("#mekanik_id", {
        plugins: ['remove_button'],
        placeholder: 'Pilih mekanik...'
    });
});
</script>
@endsection
