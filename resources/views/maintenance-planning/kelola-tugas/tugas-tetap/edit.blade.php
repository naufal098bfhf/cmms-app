@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-lg max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-700 mb-6">Edit Tugas Tetap</h2>

    <form action="{{ route('maintenance-planning.kelola-tugas.tugas-tetap.update', $tugas->id) }}"
          method="POST"
          class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Pemberi Tugas --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Pemberi Tugas</label>
            <input type="text"
                   name="pemberi_tugas"
                   value="{{ $tugas->pemberi_tugas }}"
                   readonly
                   class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed">
        </div>

        {{-- Jenis Tugas --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Jenis Tugas</label>
            <select name="jenis_tugas" id="jenis_tugas"
                    class="w-full border rounded-lg px-3 py-2" required>
                <option value="">-- Pilih Jenis Tugas --</option>
                <option value="mingguan" {{ $tugas->jenis_tugas == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ $tugas->jenis_tugas == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="tahunan" {{ $tugas->jenis_tugas == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
            </select>
        </div>

        {{-- Mingguan --}}
        <div id="form-mingguan" class="hidden">
            <label class="block text-gray-700 font-semibold mb-1">Pilih Hari</label>
            <select name="hari_mingguan" class="w-full border rounded-lg px-3 py-2">
                <option value="">-- Pilih Hari --</option>
                @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $hari)
                    <option value="{{ $hari }}" {{ $tugas->hari_mingguan == $hari ? 'selected' : '' }}>
                        {{ ucfirst($hari) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Bulanan --}}
        <div id="form-bulanan" class="hidden">
            <label class="block text-gray-700 font-semibold mb-1">Tanggal Bulanan</label>
            <input type="number" min="1" max="31"
                   name="tanggal_bulanan"
                   value="{{ $tugas->tanggal_bulanan }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        {{-- Tahunan --}}
        <div id="form-tahunan" class="hidden">
            <label class="block text-gray-700 font-semibold mb-1">Tanggal Tahunan</label>
            <input type="date"
                   name="tanggal_tahunan"
                   value="{{ $tugas->tanggal_tahunan }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        {{-- Mekanik --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Nama Mekanik</label>
            <select name="mekanik_id[]" id="mekanik_id" multiple
                    class="w-full border rounded-lg px-3 py-2">
                @foreach($mekanik as $m)
                    <option value="{{ $m->id }}"
                        {{ collect(old('mekanik_id', [$tugas->mekanik_id]))->contains($m->id) ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
            <small class="text-sm text-gray-500">
                Ketik untuk mencari, tekan Enter untuk memilih lebih dari satu mekanik.
            </small>
        </div>

        {{-- Equipment --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Equipment</label>
            <select name="equipment_id" id="equipment_id"
                    class="w-full border rounded-lg px-3 py-2" required>
                <option value="">-- Pilih Equipment --</option>
                @foreach($equipment as $eq)
                    <option value="{{ $eq->id }}"
                            data-tag="{{ $eq->tag_number }}"
                            {{ $tugas->equipment_id == $eq->id ? 'selected' : '' }}>
                        {{ $eq->name }} ({{ $eq->tag_number }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tag Number --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Tag Number</label>
            <input type="text" id="tag_number"
                   name="tag_number"
                   value="{{ $tugas->tag_number }}"
                   readonly
                   class="w-full border rounded-lg px-3 py-2 bg-gray-100">
        </div>

        {{-- EQ Class --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">EQ Class</label>
            <input type="text"
                   name="eq_class"
                   value="{{ $tugas->eq_class }}"
                   class="w-full border rounded-lg px-3 py-2" required>
        </div>

        {{-- BoM --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">BoM</label>
            <input type="text"
                   name="bom"
                   value="{{ $tugas->bom }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        {{-- Task List --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Task List</label>
            <textarea name="task_list" rows="3"
                      class="w-full border rounded-lg px-3 py-2" required>{{ $tugas->task_list }}</textarea>
        </div>

        {{-- Lokasi --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Lokasi</label>
            <input type="text"
                   name="lokasi"
                   value="{{ $tugas->lokasi }}"
                   class="w-full border rounded-lg px-3 py-2" required>
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Status</label>
            <select name="status"
                    class="w-full border rounded-lg px-3 py-2" required>
                <option value="release_order" {{ $tugas->status == 'release_order' ? 'selected' : '' }}>Release Order</option>
                <option value="dikerjakan" {{ $tugas->status == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                <option value="selesai" {{ $tugas->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('maintenance-planning.kelola-tugas.tugas-tetap.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Kembali
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenis = document.getElementById('jenis_tugas');
    const mingguan = document.getElementById('form-mingguan');
    const bulanan = document.getElementById('form-bulanan');
    const tahunan = document.getElementById('form-tahunan');
    const equipment = document.getElementById('equipment_id');
    const tag = document.getElementById('tag_number');

    function toggle() {
        mingguan.classList.add('hidden');
        bulanan.classList.add('hidden');
        tahunan.classList.add('hidden');

        if (jenis.value === 'mingguan') mingguan.classList.remove('hidden');
        if (jenis.value === 'bulanan') bulanan.classList.remove('hidden');
        if (jenis.value === 'tahunan') tahunan.classList.remove('hidden');
    }

    jenis.addEventListener('change', toggle);
    toggle();

    equipment.addEventListener('change', function () {
        const opt = equipment.options[equipment.selectedIndex];
        tag.value = opt.getAttribute('data-tag') || '';
    });
});
</script>
@endsection
