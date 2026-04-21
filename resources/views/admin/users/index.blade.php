@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Kelola User</h2>
        @if (session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
        {{ session('success') }}
    </div>
@endif

        <a href="{{ route('admin.users.create') }}"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center md:justify-start gap-2 w-full md:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah
        </a>
    </div>

    <div class="overflow-x-auto">
        <thead class="bg-gradient-to-r from-red-400 to-red-500 text-white hidden md:table-header-group">
<thead class="bg-gradient-to-r from-red-400 to-red-500 text-white hidden md:table-header-group">
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

<tr class="hover:bg-gray-50 transition block md:table-row border md:border-0 mb-4 md:mb-0 rounded-lg md:rounded-none p-2 md:p-0">

    <!-- No -->
    <td class="px-4 py-2 md:py-3 block md:table-cell">
        <span class="font-semibold md:hidden">No:</span>
        {{ $i + 1 }}
    </td>

    <!-- Nama -->
    <td class="px-4 py-2 md:py-3 block md:table-cell">
        <span class="font-semibold md:hidden">Nama:</span>
        <span class="font-semibold text-gray-700">{{ $user->name }}</span>
    </td>

    <!-- Email -->
    <td class="px-4 py-2 md:py-3 block md:table-cell break-all">
        <span class="font-semibold md:hidden">Email:</span>
        {{ $user->email }}
    </td>

    <!-- Role -->
    <td class="px-4 py-2 md:py-3 block md:table-cell">
        <span class="font-semibold md:hidden">Role:</span>
        <span class="px-3 py-1 rounded-full text-sm font-medium
            @if($user->role === 'admin') bg-purple-100 text-purple-600
            @elseif($user->role === 'mekanik') bg-blue-100 text-blue-600
            @else bg-gray-100 text-gray-600 @endif">
            {{ ucfirst($user->role) }}
        </span>
    </td>

    <!-- Department -->
    <td class="px-4 py-2 md:py-3 block md:table-cell">
        <span class="font-semibold md:hidden">Department:</span>
        {{ $user->department ?? '-' }}
    </td>

    <!-- Status -->
    <td class="px-4 py-2 md:py-3 block md:table-cell">
        <span class="font-semibold md:hidden">Status:</span>

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

    <!-- Aksi -->
    <td class="px-4 py-3 flex md:table-cell justify-start md:justify-center gap-3">
        <span class="font-semibold md:hidden">Aksi:</span>

        <div class="flex gap-3">
            <!-- Lihat -->
            <a href="{{ route('admin.users.show', $user->id) }}"
               class="text-yellow-500 hover:text-yellow-600 transition">
                👁️
            </a>

            <!-- Edit -->
            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="text-blue-500 hover:text-blue-600 transition">
                ✏️
            </a>

            <!-- Hapus -->
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Yakin hapus user ini?')"
                        class="text-red-500 hover:text-red-600 transition">
                    🗑️
                </button>
            </form>
        </div>
    </td>

</tr>

@empty
<tr>
    <td colspan="7" class="px-4 py-3 text-center text-gray-500">
        Belum ada user.
    </td>
</tr>
@endforelse
</tbody>
        </table>
    </div>
</div>
@endsection
