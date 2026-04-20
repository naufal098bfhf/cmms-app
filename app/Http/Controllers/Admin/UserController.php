<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->role && $request->role !== 'semua') {
            $query->where('role', $request->role);
        }

        if ($request->is_active !== null && $request->is_active !== 'semua') {
            $query->where('is_active', (int) $request->is_active);
        }

        $users = $query->latest()->get();
        return view('Admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin', 'mekanik', 'user', 'maintenance_planning'];
        return view('Admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,mekanik,user,maintenance_planning',
            'department' => 'nullable|string|max:100',
            'is_active'  => 'required|in:1,0',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pathPhoto = null;
        if ($request->hasFile('photo')) {
            $pathPhoto = $request->file('photo')->store('photos', 'public');
        }

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'department' => $request->department,
            'photo'      => $pathPhoto,
            'is_active'  => (int) $request->is_active,
        ];

        User::create($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = ['admin', 'mekanik', 'user', 'maintenance_planning'];
        return view('Admin.users.edit', ['user' => $user, 'roles' => $roles]);
    }

   public function update(Request $request, User $user)
{
    $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|unique:users,email,' . $user->id,
        // ❌ old_password dihapus
        'password'     => 'nullable|min:6|confirmed',
        'role'         => 'required|in:admin,mekanik,user,maintenance_planning',
        'department'   => 'nullable|string|max:100',
        'is_active'    => 'required|in:1,0',
        'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only(['name', 'email', 'role', 'department']);
    $data['is_active'] = (int) $request->is_active;

    // ================= PASSWORD TANPA PASSWORD LAMA =================
    if (!empty($request->password)) {
        $data['password'] = Hash::make($request->password);
    }

    // ================= FOTO (KODE ASLI) =================
    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $data['photo'] = $request->file('photo')->store('photos', 'public');
    }

    $user->update($data);

    return redirect()->route('admin.users.index')
                     ->with('success', 'User berhasil diperbarui!');
}

    public function destroy(User $user)
    {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus!');
    }

    public function show(User $user)
    {
        return view('Admin.users.show', compact('user'));
    }
}
