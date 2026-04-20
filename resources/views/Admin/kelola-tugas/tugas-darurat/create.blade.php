@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
        Tambah Tugas Darurat
    </h2>
    <p class="text-sm text-gray-500 mb-6">
        Input tugas darurat untuk penanganan segera
    </p>

    <form action="{{ route('admin.kelola-tugas.tugas-darurat.store') }}"
          method="POST"
          class="space-y-6">
        @csrf

        {{-- ================= INFORMASI DASAR ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Informasi Dasar
        </h3>

        {{-- Pemberi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Pemberi Tugas
            </label>
            <input type="text" value="{{ Auth::user()->name }}" readonly
                class="w-full rounded-lg border border-gray-300 bg-gray-100
                       px-4 py-2.5 text-gray-600 cursor-not-allowed">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tanggal Mulai --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
            </div>

            {{-- Tanggal Selesai --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Selesai
                </label>
                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
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
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Equipment
            </label>
            <select name="equipment_id" id="equipment_select" required
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition">
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

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tag Number
            </label>
            <input type="text" id="tag_number" readonly
                class="w-full rounded-lg border border-gray-300 bg-gray-100
                       px-4 py-2.5 text-gray-600">
        </div>

        {{-- ================= DETAIL PEKERJAAN ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Detail Pekerjaan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">EQ Class</label>
                <input type="text" id="eq_class" name="eq_class" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-100
                           px-4 py-2.5 text-gray-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">BoM</label>
                <input type="text" name="bom" value="{{ old('bom') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           px-4 py-2.5">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Task List</label>
            <textarea name="task_list" rows="4"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       px-4 py-2.5">{{ old('task_list') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       px-4 py-2.5">
                <option value="pending">Release Order</option>
                <option value="dikerjakan">Dikerjakan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-between pt-6 border-t mt-8">
            <a href="{{ route('admin.kelola-tugas.tugas-darurat.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-red-600 text-white
                       font-semibold hover:bg-red-700 transition shadow-md">
                Simpan Tugas
            </button>
        </div>

    </form>
</div>

{{-- TOM SELECT --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    new TomSelect('#mekanik_id', {
        plugins:['remove_button'],
        placeholder:'Pilih mekanik...'
    });

    const eqSelect = document.getElementById('equipment_select');
    eqSelect.addEventListener('change', e => {
        document.getElementById('tag_number').value =
            e.target.selectedOptions[0]?.dataset.tag || '';
        document.getElementById('eq_class').value =
            e.target.selectedOptions[0]?.dataset.eqclass || '';
    });
});
</script>
@endsection
