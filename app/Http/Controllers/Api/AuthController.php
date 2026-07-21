<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

     if (!$user->is_active) {
    return response()->json([
        'success' => false,
        'message' => 'Akun belum aktif'
    ],403);
}

$token = $user->createToken('mobile')->plainTextToken;

return response()->json([
    'success' => true,
    'token' => $token,
    'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'department' => $user->department,
        'photo' => $user->photo,
        'is_active' => true,
    ]
]);
}
   public function forgotPassword(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::where('name', $request->username)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Username tidak ditemukan'
        ], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Password berhasil diubah'
    ], 200);
}
public function checkUsername(Request $request)
{
    $request->validate([
        'username' => 'required',
    ]);

    $user = User::where('name', $request->username)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Username tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Username ditemukan'
    ], 200);
}

}
