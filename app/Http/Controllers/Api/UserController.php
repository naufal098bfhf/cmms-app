<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // 🔥 GET ALL
    public function index()
    {
        return response()->json(User::latest()->get());
    }

    // 🔥 DETAIL
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    // 🔥 CREATE
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'department' => $request->department,
        'is_active' => 1,
    ]);

    return response()->json($user, 201);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'       => 'required|string|max:255',
        'email'      => 'required|email',
        'role'       => 'required',
        'department' => 'nullable|string|max:255',
        'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = [
        'name'       => $request->name,
        'email'      => $request->email,
        'role'       => $request->role,
        'department' => $request->department,
        'is_active'  => (int) $request->is_active,
    ];

    // ==========================
    // PASSWORD
    // ==========================

    if (!empty($request->password)) {

        $data['password'] =
            Hash::make(
                $request->password
            );
    }

    // ==========================
    // FOTO
    // ==========================

    if (
        $request->hasFile('photo') &&
        $request->file('photo')->isValid()
    ) {

        // hapus foto lama

        if (
            $user->photo &&
            Storage::disk('public')
                ->exists($user->photo)
        ) {

            Storage::disk('public')
                ->delete($user->photo);
        }

        // simpan foto baru

        $pathPhoto =
            $request
                ->file('photo')
                ->store(
                    'photos',
                    'public'
                );

        $data['photo'] =
            $pathPhoto;
    }

    // ==========================
    // UPDATE USER
    // ==========================

    $user->update($data);

    return response()->json([
        'success' => true,
        'message' => 'User berhasil diperbarui',
        'data'    => $user->fresh(),
    ]);
}

    // 🔥 DELETE
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
