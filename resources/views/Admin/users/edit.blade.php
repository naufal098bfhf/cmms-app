@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Edit Data User</h2>
    <p class="text-sm text-gray-500 mb-6">Perbarui informasi dan keamanan akun pengguna</p>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ================= INFORMASI USER ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-6">
            Informasi User
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="mekanik" {{ $user->role === 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                    <option value="maintenance_planning" {{ $user->role === 'maintenance_planning' ? 'selected' : '' }}>
                        Maintenance Planning
                    </option>
                    <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Owner</option>
                </select>
            </div>

            {{-- Department --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <input type="text" name="department"
                    value="{{ old('department', $user->department) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="is_active"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                           px-4 py-2.5 transition">
                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        {{-- ================= FOTO PROFIL ================= --}}
        <hr class="my-8 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">Foto Profil</h3>

        <div class="flex items-center gap-6">
            <img id="previewFoto"
                src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('images/default_user.jpeg') }}"
                class="w-28 h-28 rounded-xl object-cover border shadow-sm">

            <div class="flex-1">
                <input type="file" name="photo" id="photoInput" accept="image/*"
                    class="block w-full text-sm text-gray-600
                           file:mr-4 file:py-2.5 file:px-5
                           file:rounded-lg file:border-0
                           file:bg-red-50 file:text-red-700
                           hover:file:bg-red-100 transition">
                <button type="button" id="resetFoto"
                        class="mt-2 text-sm text-red-600 hover:underline">
                    Reset Foto
                </button>
            </div>
        </div>

        {{-- ================= KEAMANAN ================= --}}
        <hr class="my-8 border-gray-200">

        <h3 class="text-lg font-semibold text-gray-700 mb-6">Keamanan Akun</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Password Baru --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50
                               focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                               px-4 py-2.5 pr-20 transition">
                    <button type="button"
                        onclick="togglePassword(this, 'password')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">
                        Lihat
                    </button>
                </div>
            </div>

            {{-- Konfirmasi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Konfirmasi Password
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50
                               focus:bg-white focus:border-red-500 focus:ring focus:ring-red-200
                               px-4 py-2.5 pr-20 transition">
                    <button type="button"
                        onclick="togglePassword(this, 'password_confirmation')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">
                        Lihat
                    </button>
                </div>
            </div>
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-between mt-10 pt-6 border-t">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Kembali
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-red-600 hover:bg-red-700
                       text-white font-semibold shadow-md transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- SCRIPT FOTO --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('photoInput');
    const preview = document.getElementById('previewFoto');
    const reset = document.getElementById('resetFoto');
    const original = preview.src;

    input.addEventListener('change', e => {
        if (e.target.files[0]) {
            preview.src = URL.createObjectURL(e.target.files[0]);
        }
    });

    reset.addEventListener('click', () => {
        input.value = '';
        preview.src = original;
    });
});

function togglePassword(btn, id) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Sembunyi';
    } else {
        input.type = 'password';
        btn.textContent = 'Lihat';
    }
}
</script>
@endsection
