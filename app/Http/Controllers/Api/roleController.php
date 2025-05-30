<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\roleModel;
use Illuminate\Http\Request;

class roleController extends Controller
{
    public function index()
    {
        $roles = roleModel::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        $role = roleModel::create([
            'role_name' => $validated['role_name'],
        ]);

        return response()->json($role, 201); // 201 Created
    }

    public function show($id)
    {
        $role = roleModel::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        return response()->json($role);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        $role = roleModel::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Hanya ubah jika ada perubahan
        if ($role->role_name !== $validated['role_name']) {
            $role->role_name = $validated['role_name'];
            $role->save();
        }

        return response()->json($role); // Kembali data role yang sudah diperbarui
    }

    public function destroy($id)
    {
        $role = roleModel::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
