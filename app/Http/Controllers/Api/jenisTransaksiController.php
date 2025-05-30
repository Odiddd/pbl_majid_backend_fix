<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\jenisTransaksiModel;
use Illuminate\Http\Request;

class jenisTransaksiController extends Controller
{
    public function index()
    {
        $jenisTransaksi = jenisTransaksiModel::all();
        return response()->json($jenisTransaksi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_name' => 'required|string|max:50',
        ]);

        $jenisTransaksi = jenisTransaksiModel::create([
            'jenis_name' => $validated['jenis_name'],
        ]);

        return response()->json($jenisTransaksi, 201); // 201 Created
    }

    public function show($id)
    {
        $jenisTransaksi = jenisTransaksiModel::find($id);

        if (!$jenisTransaksi) {
            return response()->json(['error' => 'Jenis Transaksi not found'], 404);
        }

        return response()->json($jenisTransaksi);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_name' => 'required|string|max:50',
        ]);

        $jenisTransaksi = jenisTransaksiModel::find($id);

        if (!$jenisTransaksi) {
            return response()->json(['error' => 'Jenis Transaksi not found'], 404);
        }

        // Hanya ubah jika ada perubahan
        if ($jenisTransaksi->jenis_name !== $validated['jenis_name']) {
            $jenisTransaksi->jenis_name = $validated['jenis_name'];
            $jenisTransaksi->save();
        }

        return response()->json($jenisTransaksi); // Kembali data jenis transaksi yang sudah diperbarui
    }

    public function destroy($id)
    {
        $jenisTransaksi = jenisTransaksiModel::find($id);

        if (!$jenisTransaksi) {
            return response()->json(['error' => 'Jenis Transaksi not found'], 404);
        }

        $jenisTransaksi->delete();

        return response()->json(['message' => 'Jenis Transaksi deleted successfully']);
    }
}
