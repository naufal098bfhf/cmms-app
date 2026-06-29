<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

 if (Auth::attempt($credentials, $request->filled('remember'))) {

    $request->session()->regenerate();

    $user = Auth::user();

        // Cek user aktif
        if (! $user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda belum aktif. Silakan hubungi admin.');
        }

        // Ambil role user dalam huruf kecil dan ganti spasi/underscore jadi strip
       $role = strtolower(trim(str_replace([' ', '_'], '-', $user->role)));

$role = strtolower(trim(str_replace([' ', '_'], '-', $user->role)));

switch ($role) {

    case 'owner':
        return redirect()->route('admin.dashboard')
            ->with('success', 'Selamat datang, Owner!');

    case 'admin':
        return redirect()->route('admin.dashboard')
            ->with('success', 'Selamat datang, Admin!');

    case 'mekanik':
        return redirect()->route('mekanik.dashboard')
            ->with('success', 'Selamat datang, Mekanik!');

    case 'maintenance-planning':
        return redirect()->route('maintenance-planning.dashboard')
            ->with('success', 'Selamat datang, Maintenance Planning!');

    case 'user':
        return redirect()->route('user.dashboard')
            ->with('success', 'Selamat datang, Pengguna!');

    default:
        Auth::logout();

        return redirect()->route('login')
            ->with('error', 'Role tidak dikenali: ' . $role);
}
    }

    return back()->with('error', 'Email atau password salah!')->withInput();
}
public function apiLogin(Request $request)
{
    $request->validate([

        'email' => 'required|email',

        'password' => 'required',
    ]);

    $user = User::where(
        'email',
        $request->email
    )->first();

    // =====================================
    // LOGIN GAGAL
    // =====================================

    if (
        !$user ||
        !Hash::check(
            $request->password,
            $user->password
        )
    ) {

        return response()->json([

            'status' => false,

            'message' => 'Email atau password salah',
        ], 401);
    }

    // =====================================
    // USER NONAKTIF
    // =====================================

    if (!$user->is_active) {

        return response()->json([

            'status' => false,

            'message' => 'Akun belum aktif',
        ], 403);
    }

    // =====================================
    // HAPUS TOKEN LAMA
    // =====================================

    $user->tokens()->delete();

    // =====================================
    // TOKEN SANCTUM
    // =====================================

    $token = $user
        ->createToken('mobile')
        ->plainTextToken;

return response()->json([

    'status' => true,

    'token' => $token,

    'user' => [

        'id' => $user->id,

        'name' => $user->name,

        'email' => $user->email,

        'role' => $user->role,

        'photo' => $user->photo,

        'department' => $user->department,

        'is_active' => $user->is_active,
    ],
]);
}


    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}
