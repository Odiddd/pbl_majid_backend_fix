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

        $query = transaksiModel::query();

        if ($request->has('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->has('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        return response()->json($query->get());

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'transaksi_id' => 'required|exists:transaksi,transaksi_id',
            'kategori' => 'required|string|in:pemasukan,pengeluaran',
            'jenis_transaksi_id' => 'required|exists:jenis_transaksi,jenis_transaksi_id',
            'nominal' => 'required|numeric',
            'sumber' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,valid',
            'keterangan' => 'nullable|string',
        ]);

        $user = $request->user(); // dari Sanctum
        $transaksi = transaksiModel::create([
            // 'transaksi_id' => $validated['transaksi_id'],
            'kategori' => $validated['kategori'],
            'jenis_transaksi_id' => $validated['jenis_transaksi_id'],
            'nominal' => $validated['nominal'],
            'sumber' => $validated['sumber'] ?? 'hamba Allah',
            'mengetahui' => $user->name, // otomatis dari user login
            'status' => $validated['status'] ?? 'draft',
            'keterangan' => $validated['keterangan'],
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

    public function update(Request $request, $id)
    {
        $transaksi = transaksiModel::find($id);

        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan']);
        }

        if ($transaksi->status === 'valid') {
            return response()->json(['error' => 'Transaksi yang sudah valid tidak dapat diubah']);
        }

        // Jika hanya validasi status (misal dari tombol Validasi)
        if ($request->has('status') && count($request->all()) === 1) {
            if ($transaksi->status === 'valid') {
                return response()->json(['message' => 'Transaksi sudah valid']);
            }

            $transaksi->update(['status' => 'valid']);
            return response()->json(['message' => 'Status berhasil diubah ke valid', 'data' => $transaksi]);
        }

        $validated = $request->validate([
            'kategori' => 'required|string|in:pemasukan,pengeluaran',
            'jenis_transaksi_id' => 'required|exists:jenis_transaksi,jenis_transaksi_id',
            'nominal' => 'required|numeric',
            'sumber' => 'nullable|string|max:100',
            'status' => 'required|in:draft,valid',
            'keterangan' => 'nullable|string',
        ]);

        $user = $request->user(); // dari Sanctum
        $transaksi->update([
            'kategori' => $validated['kategori'],
            'jenis_transaksi_id' => $validated['jenis_transaksi_id'],
            'nominal' => $validated['nominal'],
            'sumber' => $validated['sumber'] ?? 'hamba Allah',
            'mengetahui' => $user->name, // otomatis dari user login
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
        ]);

        return response()->json($transaksi);
    }

    // public function summary()
    // {
    //     $transaksiValid = transaksiModel::where('status', 'valid')->get();

    //     $pemasukan = $transaksiValid->where('kategori', 'pemasukan')->sum('nominal');
    //     $pengeluaran = $transaksiValid->where('kategori', 'pengeluaran')->sum('nominal');
    //     $draft = transaksiModel::where('status', 'draft')->count();

    //     $tunai = $transaksiValid
    //         ->filter(fn($item) => $item->jenis_transaksi->jenis_name === 'tunai')
    //         ->sum(fn($item) => $item->kategori === 'pemasukan' ? $item->nominal : -$item->nominal);

    //     $rekening = $transaksiValid
    //         ->filter(fn($item) => $item->jenis_transaksi->jenis_name === 'rekening')
    //         ->sum(fn($item) => $item->kategori === 'pemasukan' ? $item->nominal : -$item->nominal);

    //     return response()->json([
    //         'pemasukan' => $pemasukan,
    //         'pengeluaran' => $pengeluaran,
    //         'draft' => $draft,
    //         'tunai' => $tunai,
    //         'rekening' => $rekening,
    //         'total_saldo' => $tunai + $rekening,
    //     ]);
    // }

    public function summary()
{
    // Include relationship untuk jenis_transaksi
    $transaksiValid = transaksiModel::with('jenis_transaksi')
        ->where('status', 'valid')
        ->get();

    $pemasukan = $transaksiValid->where('kategori', 'pemasukan')->sum('nominal');
    $pengeluaran = $transaksiValid->where('kategori', 'pengeluaran')->sum('nominal');
    $draft = transaksiModel::where('status', 'draft')->count();

    // Pastikan jenis_transaksi ada sebelum akses jenis_name
    $tunai = $transaksiValid
        ->filter(fn($item) => $item->jenis_transaksi && $item->jenis_transaksi->jenis_name === 'tunai')
        ->sum(fn($item) => $item->kategori === 'pemasukan' ? $item->nominal : -$item->nominal);

    $rekening = $transaksiValid
        ->filter(fn($item) => $item->jenis_transaksi && $item->jenis_transaksi->jenis_name === 'rekening')
        ->sum(fn($item) => $item->kategori === 'pemasukan' ? $item->nominal : -$item->nominal);

    return response()->json([
        'pemasukan' => $pemasukan,
        'pengeluaran' => $pengeluaran,
        'draft' => $draft,
        'saldo_tunai' => $tunai,
        'saldo_rekening' => $rekening,
        'total_saldo' => $tunai + $rekening,
    ]);
}

    public function destroy($id)
    {
        $transaksi = transaksiModel::find($id);

        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->status === 'valid') {
            return response()->json(['error' => 'Transaksi yang sudah valid tidak dapat dihapus'], 403);
        }

        $transaksi->delete();

        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }    


}
