@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Tambah User</h2>
    <p class="text-sm text-gray-500 mb-6">Lengkapi data pengguna dengan benar</p>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- DATA UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-green-500 focus:ring focus:ring-green-200
                           px-4 py-2.5 transition"
                    required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-green-500 focus:ring focus:ring-green-200
                           px-4 py-2.5 transition"
                    required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-green-500 focus:ring focus:ring-green-200
                           px-4 py-2.5 transition"
                    required>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-green-500 focus:ring focus:ring-green-200
                           px-4 py-2.5 transition"
                    required>
                    <option disabled selected>Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="mekanik">Mekanik</option>
                    <option value="user">User</option>
                    <option value="maintenance_planning">Maintenance Planning</option>
                </select>
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Department --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <input type="text" name="department" value="{{ old('department') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-green-500 focus:ring focus:ring-green-200
                           px-4 py-2.5 transition">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="is_active"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50
                           focus:bg-white focus:border-green-500 focus:ring focus:ring-green-200
                           px-4 py-2.5 transition">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
        </div>

        {{-- GARIS PEMBATAS --}}
        <hr class="my-8 border-gray-200">

        {{-- FOTO PROFIL --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Foto Profil</label>

            <div class="flex items-center gap-6">
                <img id="previewImage"
                     src="{{ asset('images/default_user.jpeg') }}"
                     class="w-28 h-28 rounded-xl object-cover border shadow-sm">

                <div class="flex-1">
                    <input type="file" name="photo" id="photoInput" accept="image/*"
                        class="block w-full text-sm text-gray-600
                               file:mr-4 file:py-2.5 file:px-5
                               file:rounded-lg file:border-0
                               file:bg-green-50 file:text-green-700
                               hover:file:bg-green-100 transition">
                    <p class="text-xs text-gray-500 mt-2">Format JPG / PNG · Maks 2MB</p>
                </div>
            </div>
        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-3 mt-10">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-green-600 text-white font-semibold
                       hover:bg-green-700 transition shadow-md">
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- PREVIEW FOTO --}}
<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById('previewImage').src = reader.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
@endsection
