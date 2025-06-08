<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        $users = UserModel::with('role')->get();
        // return response()->json($users);
        return response()->json([
            'success' => true, // ✅ Tambahan response format konsisten
            'data' => $users
        ]);
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

        $user = UserModel::create([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            // 'password' => $validated['password'],
            'password' => Hash::make($validated['password']),  // ✅ Password dienkripsi
        ]);

        return response()->json([
            'success' => true, // ✅ Format response lebih rapi
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function show($id)
    {
        $user = UserModel::with('role')->find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404); // ✅ Format error lebih konsisten
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:role,role_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id .',user_id', // fix: allow current email
            'password' => 'nullable|string|min:8',
        ]);

        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);      

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diedit.',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus.'
        ]);
    }
}
