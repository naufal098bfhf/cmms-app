<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index()
    {
        $users = User::all();
        return view('admin.kelola-user', compact('users'));
    }

    /**
     * Tampilkan form buat user baru
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {

        $request->validate([
            'id_user'    => 'nullable|string|max:50',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,mekanik',
            'department' => 'nullable|string|max:100',
            'is_active'  => 'required|in:1,0',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ Simpan foto ke folder storage/app/public/photos
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->storeAs(
                'photos',
                time() . '_' . $request->file('photo')->getClientOriginalName(),
                'public'
            );
        }

        User::create([
            'id_user'    => $request->id_user,
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'department' => $request->department,
            'is_active'  => $request->is_active,
            'photo'      => $photoPath,
        ]);

        // ✅ Tambah pesan sukses
        return redirect()->route('admin.kelola-user')
            ->with('success', '✅ User baru berhasil ditambahkan!');
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,mekanik',
            'department' => 'nullable|string|max:100',
            'is_active'  => 'required|in:1,0',
            'password'   => 'nullable|min:6',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role', 'department', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // ✅ Hapus foto lama dan simpan baru
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $data['photo'] = $request->file('photo')->storeAs(
                'photos',
                time() . '_' . $request->file('photo')->getClientOriginalName(),
                'public'
            );
        }

        $user->update($data);

        // ✅ Tambah pesan sukses edit
        return redirect()->route('admin.kelola-user')
            ->with('success', ' Data user berhasil diperbarui!');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.kelola-user')
            ->with('success', ' User berhasil dihapus!');
    }
}
