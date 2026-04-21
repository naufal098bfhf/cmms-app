@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-700 mb-6">Detail User</h2>
<div class="flex flex-col md:flex-row items-start gap-8">
  {{-- Foto user --}}
<div class="flex-shrink-0">
    @if(!empty($user->photo) && file_exists(public_path('storage/' . $user->photo)))
        {{-- ✅ Foto dari storage --}}
        <img src="{{ asset('storage/' . $user->photo) }}"
             alt="Foto {{ $user->name }}"
             class="w-40 h-40 rounded-xl object-cover shadow-md border">
    @else
        {{-- ✅ Foto default --}}
        <img src="{{ asset('images/default-user.png') }}"
             alt="Foto Default"
             class="w-40 h-40 rounded-xl object-cover shadow-md border">
    @endif
</div>


        {{-- Data user --}}
        <div class="flex-1">
            <table class="w-full text-gray-700">
                <tr class="border-b">
                    <td class="py-2 font-semibold w-32">ID</td>
                    <td class="py-2">{{ $user->id }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold">Nama</td>
                    <td class="py-2">{{ $user->name }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold">Email</td>
                    <td class="py-2">{{ $user->email }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold">Role</td>
                    <td class="py-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($user->role === 'admin') bg-purple-100 text-purple-600
                            @elseif($user->role === 'mekanik') bg-blue-100 text-blue-600
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold">Department</td>
                    <td class="py-2">{{ $user->department ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Status</td>
                    <td class="py-2">
                        @if($user->status === 'aktif' || $user->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">
                                Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-medium">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Tombol kembali --}}
    <div class="mt-8">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection
