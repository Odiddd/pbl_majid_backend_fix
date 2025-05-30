<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\transaksiModel;
use Illuminate\Http\Request;

class transaksiController extends Controller
{
    public function index()
    {
        $transaksi = transaksiModel::all();
        $transaksi = transaksiModel::with('jenis_transaksi')->get();
        return response()->json($transaksi);

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'transaksi_id' => 'required|exists:transaksi,transaksi_id',
            'kategori' => 'required|string|in:pemasukan,pengeluaran',
            'jenis_transaksi_id' => 'required|exists:jenis_transaksi,jenis_transaksi_id',
            'nominal' => 'required|numeric',
            'sumber' => 'nullable|string|max:100',
            'mengetahui' => 'required|string|max:50',
            'status' => 'nullable|in:draft,valid',
        ]);

        $transaksi = transaksiModel::create([
            // 'transaksi_id' => $validated['transaksi_id'],
            'kategori' => $validated['kategori'],
            'jenis_transaksi_id' => $validated['jenis_transaksi_id'],
            'nominal' => $validated['nominal'],
            'sumber' => $validated['sumber'] ?? 'hamba Allah',
            'mengetahui' => $validated['mengetahui'],
            'status' => $validated['status'] ?? 'draft',
        ]);

        return response()->json($transaksi, 201);
    }

    public function show($id)
    {
        $t = transaksiModel::with('jenis_transaksi')->find($id);

        if (!$t) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json($t);
    }
}
