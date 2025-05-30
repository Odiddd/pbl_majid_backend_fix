<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TempatReservasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TempatReservasiController extends Controller
{
    public function index()
    {
        return response()->json(TempatReservasiModel::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'nullable|numeric',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string',
            'biaya' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('image/tempat_reservasi'), $imageName);
            $validated['image'] = 'image/tempat_reservasi/' . $imageName;
        }

        $data = TempatReservasiModel::create($validated);

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $data = TempatReservasiModel::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
{
    Log::info('Memulai proses update tempat_reservasi', ['id' => $id]);

    $data = TempatReservasiModel::find($id);

    if (!$data) {
        Log::error('Data tempat_reservasi tidak ditemukan', ['id' => $id]);
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    Log::info('Data ditemukan, mulai validasi...', ['data_lama' => $data]);

    try {
        $validated = $request->validate([
            'lokasi' => 'sometimes|required|string|max:255',
            'kapasitas' => 'nullable|numeric',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string',
            'biaya' => 'nullable|numeric',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validasi gagal', ['errors' => $e->errors()]);
        return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
    }

    Log::info('Data tervalidasi', ['validated' => $validated]);

    if ($request->hasFile('image')) {
        Log::info('File image ditemukan, mulai upload...');

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('image/tempat_reservasi'), $imageName);
        $validated['image'] = 'image/tempat_reservasi/' . $imageName;

        Log::info('Upload gambar berhasil', ['path' => $validated['image']]);
    }

    $updateStatus = $data->update($validated);

    Log::info('Update status', ['updated' => $updateStatus, 'data_baru' => $data->fresh()]);

    return response()->json([
        'message' => $updateStatus ? 'Data berhasil diupdate' : 'Tidak ada perubahan data',
        'data' => $data->fresh()
    ]);
}

    public function destroy($id)
    {
        $data = TempatReservasiModel::findOrFail($id);

        // (Opsional) Hapus file gambar dari server
        // if ($data->image && file_exists(public_path($data->image))) {
        //     unlink(public_path($data->image));
        // }

        $data->delete();

        return response()->json(['message' => 'Tempat reservasi berhasil dihapus']);
    }
}
