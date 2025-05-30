<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\kegiatanModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = kegiatanModel::all();
        return response()->json($kegiatan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'keterangan' => 'nullable',
            
        ]);

        $data = $request->only([
            'nama_kegiatan',
            'isi',
            'tanggal',
            'waktu_mulai',
            'waktu_selesai',
            'lokasi',
            'keterangan',
        ]);

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('kegiatan', 'public');
            $data['image'] = $imagePath;
        }

        // Hitung status otomatis
        $today = Carbon::now()->startOfDay();
        $tanggal = Carbon::parse($data['tanggal'])->startOfDay();

        if ($tanggal->greaterThan($today)) {
            $data['status'] = 'dijadwalkan';
        } elseif ($tanggal->equalTo($today)) {
            $data['status'] = 'dilaksanakan';
        } else {
            $data['status'] = 'selesai';
        }

        $kegiatan = kegiatanModel::create($data);
        return response()->json($kegiatan, 201);
    }

    public function show($id)
    {
        $kegiatan = kegiatanModel::findOrFail($id);
        return response()->json($kegiatan);
    }

    public function update(Request $request, $id)
    {
        $kegiatan = kegiatanModel::findOrFail($id);

        $request->validate([
            'nama_kegiatan' => 'required|string',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required|string',
            'status' => 'required|string|in:dijadwalkan,dilaksanakan,selesai,dibatalkan', // validasi status
            'image' => 'nullable|image|max:2048',
            'keterangan' => 'nullable',
        ]);

        // $data = $request->only([
        //     'nama_kegiatan',
        //     'isi',
        //     'tanggal',
        //     'waktu_mulai',
        //     'waktu_selesai',
        //     'lokasi',
        // ]);

        $data = [
            'nama_kegiatan' => $request->nama_kegiatan,
            'isi' => $request->isi,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
        ];

        // // Simpan gambar baru jika ada
        // if ($request->hasFile('image')) {
        //     // Hapus gambar lama
        //     if ($kegiatan->image) {
        //         Storage::disk('public')->delete($kegiatan->image);
        //     }

        //     $imagePath = $request->file('image')->store('kegiatan', 'public');
        //     $data['image'] = $imagePath;
        // }

        // Hapus gambar jika ada flag remove_image dan tidak upload gambar baru
        if ($request->has('remove_image') && !$request->hasFile('image')) {
            if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
                Storage::disk('public')->delete($kegiatan->image);
            }
            $data['image'] = null;
        }

        // Jika upload gambar baru
        if ($request->hasFile('image')) {
            if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
                Storage::disk('public')->delete($kegiatan->image);
            }

            $data['image'] = $request->file('image')->store('kegiatan', 'public');
        }

        // Update status otomatis
        // $today = Carbon::now()->startOfDay();
        // $tanggal = Carbon::parse($data['tanggal'])->startOfDay();

        // if ($tanggal->greaterThan($today)) {
        //     $data['status'] = 'dijadwalkan';
        // } elseif ($tanggal->equalTo($today)) {
        //     $data['status'] = 'dilaksanakan';
        // } else {
        //     $data['status'] = 'selesai';
        // }

        if ($request->has('status')) {
        $data['status'] = $request->status; // pakai status dari frontend
        } else {
        // fallback jika tidak ada status, set otomatis
        $today = Carbon::now()->startOfDay();
        $tanggal = Carbon::parse($data['tanggal'])->startOfDay();

        if ($tanggal->greaterThan($today)) {
            $data['status'] = 'dijadwalkan';
        } elseif ($tanggal->equalTo($today)) {
            $data['status'] = 'dilaksanakan';
        } else {
            $data['status'] = 'selesai';
        }
    }

        $kegiatan->update($data);
        return response()->json($kegiatan);
    }

    public function destroy($id)
    {
        $kegiatan = kegiatanModel::findOrFail($id);

        if ($kegiatan->image) {
            Storage::disk('public')->delete($kegiatan->image);
        }

        $kegiatan->delete();
        return response()->json(null, 204);
    }
}
