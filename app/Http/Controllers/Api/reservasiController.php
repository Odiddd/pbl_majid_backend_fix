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
            'nama_pemesan' => 'required|string|max:255',
            'kontak_pemesan' => 'required|string|max:255',
            'tempat_reservasi_id' => 'required|exists:tempat_reservasi,tempat_reservasi_id',
            'nama_acara' => 'required|string|max:255',
            'tanggal_acara' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'jumlah_tamu' => 'nullable|numeric',
            'status_reservasi' => 'nullable|in:menunggu,dikonfirmasi,dijadwalkan,dilaksanakan,selesai,batal',
            'keterangan' => 'nullable|string',
        ]);

        $reservasi = reservasiModel::create([
            'nama_pemesan' => $request->nama_pemesan,
            'kontak_pemesan' => $request->kontak_pemesan,
            'tempat_reservasi_id' => $request->tempat_reservasi_id,
            'nama_acara' => $request->nama_acara,
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'jumlah_tamu' => $request->jumlah_tamu,
            'status_reservasi' => $request->status_reservasi ?? 'dijadwalkan',
            'mengetahui' => $request->user()->name ?? null,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Reservasi berhasil disimpan.',
            'data' => $reservasi
        ], 201);
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
            'nama_pemesan' => 'sometimes|required|string|max:255',
            'kontak_pemesan' => 'sometimes|required|string|max:255',
            'tempat_reservasi_id' => 'sometimes|required|exists:tempat_reservasi,tempat_reservasi_id',
            'nama_acara' => 'sometimes|required|string|max:255',
            'tanggal_acara' => 'sometimes|required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'jumlah_tamu' => 'nullable|numeric',
            'status_reservasi' => 'sometimes|required|in:menunggu,dikonfirmasi,dijadwalkan,dilaksanakan,selesai,batal',
            'keterangan' => 'nullable|string',
        ]);

        $reservasi->update([
            'nama_pemesan' => $request->nama_pemesan,
            'kontak_pemesan' => $request->kontak_pemesan,
            'tempat_reservasi_id' => $request->tempat_reservasi_id,
            'nama_acara' => $request->nama_acara,
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'jumlah_tamu' => $request->jumlah_tamu,
            'status_reservasi' => $request->status_reservasi,
            'mengetahui' => $request->user()->name ?? $reservasi->mengetahui,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Reservasi berhasil diperbarui.',
            'data' => $reservasi
        ]);
    }

    public function destroy($id)
    {
        $reservasi = reservasiModel::findOrFail($id);
        $reservasi->delete();

        return response()->json(['message' => 'Reservasi berhasil dihapus']);
    }

    // Method tambahan untuk update status
    public function updateStatus(Request $request, $id)
    {
        $reservasi = reservasiModel::findOrFail($id);

        $validated = $request->validate([
            'status_reservasi' => 'required|in:menunggu,dikonfirmasi,dijadwalkan,dilaksanakan,selesai,batal',
            'keterangan' => 'nullable|string',
        ]);

        $reservasi->update($validated);
        return response()->json($reservasi);
    }
}