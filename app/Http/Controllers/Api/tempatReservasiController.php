<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tempatReservasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class tempatReservasiController extends Controller
{
    public function index()
    {
        return response()->json(tempatReservasiModel::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|numeric',
            'biaya' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tempat_reservasi', 'public');
        }

        $tempat = tempatReservasiModel::create([
            'lokasi' => $request->lokasi,
            'kapasitas' => $request->kapasitas,
            'biaya' => $request->biaya,
            'keterangan' => $request->keterangan,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Tempat reservasi berhasil disimpan.',
            'data' => $tempat
        ], 201);
    }

    public function show($id)
    {
        $tempat = tempatReservasiModel::findOrFail($id);
        return response()->json($tempat);
    }

    public function update(Request $request, $id)
    {
        $tempat = tempatReservasiModel::findOrFail($id);

        $request->validate([
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|numeric',
            'biaya' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'lokasi' => $request->lokasi,
            'kapasitas' => $request->kapasitas,
            'biaya' => $request->biaya,
            'keterangan' => $request->keterangan,
        ];

        if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            if ($tempat->image && Storage::exists('public/' . $tempat->image)) {
                Storage::delete('public/' . $tempat->image);
            }
            $tempat->image = null;
        }

        if ($request->hasFile('image')) {
            if ($tempat->image && Storage::disk('public')->exists($tempat->image)) {
                Storage::disk('public')->delete($tempat->image);
            }

            $data['image'] = $request->file('image')->store('tempat_reservasi', 'public');
        }

        $tempat->update($data);

        return response()->json([
            'message' => 'Tempat reservasi berhasil diperbarui.',
            'data' => $tempat
        ]);
    }

    public function destroy($id)
    {
        $tempat = tempatReservasiModel::findOrFail($id);

        if ($tempat->image && Storage::disk('public')->exists($tempat->image)) {
            Storage::disk('public')->delete($tempat->image);
        }

        $tempat->delete();

        return response()->json([
            'message' => 'Tempat reservasi berhasil dihapus.'
        ]);
    }
}
