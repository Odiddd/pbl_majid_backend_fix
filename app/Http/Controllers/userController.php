<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\userModel;
use Illuminate\Http\Request;

class userControllerOld extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        $users = userModel::all();
        return response()->json($users);
    }

    // Menambahkan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = userModel::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json($user, 201); // 201 berarti berhasil dibuat
    }
}
