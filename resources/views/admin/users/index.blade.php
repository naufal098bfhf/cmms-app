@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Kelola User</h2>
        @if (session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
        {{ session('success') }}
    </div>
@endif

        <a href="{{ route('admin.users.create') }}"
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <thead class="bg-gradient-to-r from-red-400 to-red-500 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Department</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $i => $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-700">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($user->role === 'admin') bg-purple-100 text-purple-600
                            @elseif($user->role === 'mekanik') bg-blue-100 text-blue-600
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $user->department ?? '-' }}</td>
                    <td class="px-4 py-3">
                   @if($user->is_active == 1)
                        <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">
                            Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-medium">
                            Nonaktif
                        </span>
                    @endif

                    </td>
                  <td class="px-4 py-3 flex justify-center gap-3">

                {{-- Lihat --}}
                <a href="{{ route('admin.users.show', $user->id) }}"
                class="text-yellow-500 hover:text-yellow-600 transition"
                title="Lihat">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0
                                8.268 2.943 9.542 7-1.274 4.057-5.065
                                7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </a>

                {{-- Edit --}}
                <a href="{{ route('admin.users.edit', $user->id) }}"
                class="text-blue-500 hover:text-blue-600 transition"
                title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.121
                                2.121 0 013 3L12 14H9v-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 13v6a2 2 0 01-2 2H7a2 2 0
                                01-2-2V7a2 2 0 012-2h6"/>
                    </svg>
                </a>

                {{-- Hapus --}}
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Yakin hapus user ini?')"
                            class="text-red-500 hover:text-red-600 transition"
                            title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0
                                    0116.138 21H7.862a2 2 0
                                    01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0
                                    011 1v2H9V4a1 1 0 011-1z"/>
                        </svg>
                    </button>
                </form>

            </td>


                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">Belum ada user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
