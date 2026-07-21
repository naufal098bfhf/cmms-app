<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // =========================
    // GET ALL USERS
    // =========================
    public function index()
    {
        return response()->json(User::latest()->get());
    }

    // =========================
    // DETAIL USER
    // =========================
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }
    // =========================
    // CREATE USER
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required',
            'department' => 'nullable|string|max:255',
            'is_active'  => 'nullable',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        Log::info([
            'CONTENT_TYPE' => $request->header('Content-Type'),
            'HAS_FILE'     => $request->hasFile('photo'),
            'FILES'        => $request->allFiles(),
            'DATA'         => $request->except('password'),
        ]);

        $pathPhoto = null;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            Log::info([
                'ORIGINAL_NAME' => $file->getClientOriginalName(),
                'MIME'          => $file->getMimeType(),
                'SIZE'          => $file->getSize(),
                'VALID'         => $file->isValid(),
            ]);

            if ($file->isValid()) {

                $pathPhoto = $file->store('photos', 'public');

                Log::info([
                    'PHOTO_SAVED' => $pathPhoto,
                ]);
            }
        } else {

            Log::warning('PHOTO TIDAK DITERIMA LARAVEL');
        }

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'department' => $request->department,
            'photo'      => $pathPhoto,
            'is_active'  => (int) ($request->is_active ?? 1),
        ]);

        Log::info([
            'USER_CREATED' => $user->toArray(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data'    => $user,
        ], 201);
    }

    // =========================
    // UPDATE USER
    // =========================
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

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if (
            $request->hasFile('photo') &&
            $request->file('photo')->isValid()
        ) {

            if (
                $user->photo &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $data['photo'] = $request
                ->file('photo')
                ->store('photos', 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data'    => $user->fresh(),
        ]);
    }

    // =========================
    // DELETE USER
    // =========================
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (
            $user->photo &&
            Storage::disk('public')->exists($user->photo)
        ) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}

