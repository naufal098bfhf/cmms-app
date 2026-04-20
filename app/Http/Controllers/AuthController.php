<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah!',
        ], 401);
    }

    $user = Auth::user();

    if (!$user->is_active) {
        Auth::logout();
        return response()->json([
            'status' => false,
            'message' => 'Akun belum aktif',
        ], 403);
    }

    $role = strtolower(trim(str_replace([' ', '_'], '-', $user->role)));

    // 🔥 TOKEN MANUAL (BUKAN SANCTUM)
    $token = base64_encode($user->email . '|' . now());

    return response()->json([
        'status' => true,
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $role,
        ],
    ]);
    }
}
