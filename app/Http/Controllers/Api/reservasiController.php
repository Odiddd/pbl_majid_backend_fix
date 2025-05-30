<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\reservasiModel;
use Illuminate\Http\Request;

class reservasiController extends Controller
{
    public function index()
    {
        return response()->json(reservasiModel::with('tempatReservasi')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemesan' => 'required|string',
            'kontak_pemesan' => 'required|string',
            'tempat_reservasi_id' => 'required|exists:tempat_reservasi,tempat_reservasi_id',
            'nama_acara' => 'required|string',
            'tanggal_acara' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i:s',
            'waktu_selesai' => 'nullable|date_format:H:i:s|after_or_equal:waktu_mulai',
            'jumlah_tamu' => 'nullable|numeric',
            'status_reservasi' => 'required|in:menunggu,dikonfirmasi,dijadwalkan,dilaksanakan,selesai,batal',
            'mengetahui' => 'nullable|string',
            'tagihan' => 'nullable|numeric',
            'status_pembayaran' => 'required|in:pending,success,failed',
            'keterangan' => 'nullable|string',
            'masuk_transaksi' => 'boolean',
        ]);

        $reservasi = reservasiModel::create($validated);
        return response()->json($reservasi, 201);
    }

    public function show($id)
    {
        $reservasi = reservasiModel::with('tempatReservasi')->findOrFail($id);
        return response()->json($reservasi);
    }

    public function update(Request $request, $id)
    {
        $reservasi = reservasiModel::findOrFail($id);

        $validated = $request->validate([
            'nama_pemesan' => 'sometimes|required|string',
            'kontak_pemesan' => 'sometimes|required|string',
            'tempat_reservasi_id' => 'sometimes|required|exists:tempat_reservasi,tempat_reservasi_id',
            'nama_acara' => 'sometimes|required|string',
            'tanggal_acara' => 'sometimes|required|date',
            'waktu_mulai' => 'nullable|date_format:H:i:s',
            'waktu_selesai' => 'nullable|date_format:H:i:s|after_or_equal:waktu_mulai',
            'jumlah_tamu' => 'nullable|numeric',
            'status_reservasi' => 'sometimes|required|in:menunggu,dikonfirmasi,dijadwalkan,dilaksanakan,selesai,batal',
            'mengetahui' => 'nullable|string',
            'tagihan' => 'nullable|numeric',
            'status_pembayaran' => 'sometimes|required|in:pending,success,failed',
            'keterangan' => 'nullable|string',
            'masuk_transaksi' => 'boolean',
        ]);

        $reservasi->update($validated);
        return response()->json($reservasi);
    }

    public function destroy($id)
    {
        $reservasi = reservasiModel::findOrFail($id);
        $reservasi->delete();

        return response()->json(['message' => 'Reservasi berhasil dihapus']);
    }
}
