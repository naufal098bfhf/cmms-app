@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
        Tambah Tugas Tetap
    </h2>
    <p class="text-sm text-gray-500 mb-6">
        Buat dan atur tugas maintenance berkala
    </p>

    <form action="{{ route('admin.kelola-tugas.tugas-tetap.store') }}"
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

        {{-- Jenis --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Jenis Tugas
            </label>
            <select id="jenis_tugas" name="jenis_tugas"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
                <option value="" disabled {{ old('jenis_tugas') ? '' : 'selected' }}>
                    Pilih Jenis Tugas
                </option>
                <option value="mingguan" {{ old('jenis_tugas')=='mingguan'?'selected':'' }}>Mingguan</option>
                <option value="bulanan" {{ old('jenis_tugas')=='bulanan'?'selected':'' }}>Bulanan</option>
                <option value="tahunan" {{ old('jenis_tugas')=='tahunan'?'selected':'' }}>Tahunan</option>
            </select>
        </div>

        {{-- Mingguan --}}
        <div id="form-mingguan" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Hari Mingguan
            </label>
<select name="hari_mingguan"
    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
    <option value="">Pilih Hari</option>
    <option value="senin">Senin</option>
    <option value="selasa">Selasa</option>
    <option value="rabu">Rabu</option>
    <option value="kamis">Kamis</option>
    <option value="jumat">Jumat</option>
    <option value="sabtu">Sabtu</option>
    <option value="minggu">Minggu</option>
</select>

        </div>

        {{-- Bulanan --}}
        <div id="form-bulanan" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Bulanan
            </label>
            <input type="number" min="1" max="31" name="tanggal_bulanan"
                value="{{ old('tanggal_bulanan') }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
        </div>

        {{-- Tahunan --}}
        <div id="form-tahunan" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Tahunan
            </label>
            <input type="date" name="tanggal_tahunan"
                value="{{ old('tanggal_tahunan') }}"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
        </div>

        {{-- ================= PERSONEL ================= --}}
        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Penugasan Personel
        </h3>

        {{-- Mekanik --}}
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

        {{-- Equipment --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Equipment
            </label>
            <select name="equipment_id" id="equipment-select"
                class="w-full rounded-lg border border-gray-300 bg-gray-50
                       focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                       px-4 py-2.5 transition"
                required>
                <option value="">Pilih Equipment</option>
                @foreach($equipment as $eq)
                    <option value="{{ $eq->id }}" data-tag="{{ $eq->tag_number }}">
                        {{ $eq->name }} ({{ $eq->tag_number }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tag --}}
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Eq. Class</label>
                <input type="text" name="eq_class"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">BoM</label>
                <input type="text" name="bom"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Task List</label>
            <textarea name="task_list" rows="4"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5" required></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="lokasi"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-between pt-6 border-t mt-8">
            <a href="{{ route('admin.kelola-tugas.tugas-tetap.index') }}"
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
    const jenis = document.getElementById('jenis_tugas');
    const m = document.getElementById('form-mingguan');
    const b = document.getElementById('form-bulanan');
    const t = document.getElementById('form-tahunan');

    function toggle(){
        m.classList.add('hidden'); b.classList.add('hidden'); t.classList.add('hidden');
        if(jenis.value==='mingguan') m.classList.remove('hidden');
        if(jenis.value==='bulanan') b.classList.remove('hidden');
        if(jenis.value==='tahunan') t.classList.remove('hidden');
    }
    jenis.addEventListener('change', toggle);
    toggle();

    new TomSelect('#mekanik_id', {
        plugins:['remove_button'],
        placeholder:'Pilih mekanik...'
    });

    document.getElementById('equipment-select').addEventListener('change', e => {
        document.getElementById('tag_number').value =
            e.target.selectedOptions[0]?.dataset.tag || '';
    });
});
</script>
@endsection
