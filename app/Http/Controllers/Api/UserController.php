<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department' => $request->department,
        ]);

        return response()->json($user);
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
