<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\userModel;
use Illuminate\Http\Request;

class userController extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        $users = userModel::with('role')->get();
        return response()->json($users);
    }

    // Menambahkan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:role,role_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = userModel::create([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        return response()->json($user, 201); // 201 berarti berhasil dibuat
    }

    public function show($id)
    {
        $user = userModel::with('role')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:role,role_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id .',user_id', // fix: allow current email
            'password' => 'required|string|min:8',
        ]);

        $user = userModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);      

        return response()->json($user); // Kembali data role yang sudah diperbarui
    }

    public function destroy($id)
    {
        $user = userModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
