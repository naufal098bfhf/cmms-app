@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
        Edit Tugas Darurat
    </h2>
    <p class="text-sm text-gray-500 mb-6">
        Perbarui tugas maintenance darurat
    </p>

    <form action="{{ route('admin.kelola-tugas.tugas-darurat.update', $tugasDarurat->id) }}"
          method="POST"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ================= INFORMASI WAKTU ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Informasi Waktu
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai"
                    value="{{ old('tgl_mulai', $tugasDarurat->tgl_mulai) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai"
                    value="{{ old('tgl_selesai', $tugasDarurat->tgl_selesai) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
            </div>
        </div>

        {{-- ================= PERSONEL ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Penugasan Personel
        </h3>

       <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Mekanik
            </label>
            <select name="mekanik_id[]" id="mekanik_id" multiple required>
                @foreach($mekanik as $m)
                    <option value="{{ $m->id }}"
                        {{ collect(old('mekanik_id'))->contains($m->id) ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">
                Bisa pilih lebih dari satu mekanik
            </p>
        </div>


        {{-- ================= EQUIPMENT ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Equipment
        </h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
            <select id="equipment_id" name="equipment_id"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition">
                <option value="">Pilih Equipment</option>
                @foreach($equipments as $eq)
                    <option value="{{ $eq->id }}"
                        data-tag="{{ $eq->tag_number }}"
                        data-class="{{ $eq->eq_class }}"
                        {{ old('equipment_id', $tugasDarurat->equipment_id) == $eq->id ? 'selected' : '' }}>
                        {{ $eq->name }} ({{ $eq->tag_number }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tag Number</label>
            <input id="tag_number" type="text" readonly
                value="{{ old('tag_number', $tugasDarurat->tag_number) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-gray-600">
        </div>

        {{-- ================= DETAIL PEKERJAAN ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Detail Pekerjaan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">EQ Class</label>
                <input id="eq_class" type="text" name="eq_class"
                    value="{{ old('eq_class', $tugasDarurat->eq_class) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">BoM</label>
                <input type="text" name="bom"
                    value="{{ old('bom', $tugasDarurat->bom) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Task List</label>
            <textarea name="task_list" rows="4"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">{{ old('task_list', $tugasDarurat->task_list) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="lokasi"
                value="{{ old('lokasi', $tugasDarurat->lokasi) }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                <option value="release_order" {{ old('status', $tugasDarurat->status) == 'release_order' ? 'selected' : '' }}>Release Order</option>
                <option value="dikerjakan" {{ old('status', $tugasDarurat->status) == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                <option value="selesai" {{ old('status', $tugasDarurat->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-between pt-6 border-t mt-8">
            <a href="{{ route('admin.kelola-tugas.tugas-darurat.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-red-600 text-white font-semibold
                       hover:bg-red-700 transition shadow-md">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
{{-- TOM SELECT --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

{{-- AUTO TAG & EQ CLASS --}}
<script>
document.getElementById('equipment_id').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('tag_number').value = opt?.dataset.tag || '';
    document.getElementById('eq_class').value = opt?.dataset.class || '';
});


document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('mekanik_id')) {
        new TomSelect('#mekanik_id', {
            plugins: ['remove_button'],
            placeholder: 'Pilih mekanik...',
            persist: false,
            create: false
        });
    }
});
</script>
@endsection
