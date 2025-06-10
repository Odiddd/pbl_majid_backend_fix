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
    
    private function updateStatusIfNeeded($kegiatan)
    {
        $today = Carbon::now()->startOfDay();
        $tanggal = Carbon::parse($kegiatan->tanggal)->startOfDay();

        if ($kegiatan->status === 'dijadwalkan' && $tanggal->equalTo($today)) {
            $kegiatan->update(['status' => 'dilaksanakan']);
        }

        if ($kegiatan->status === 'dilaksanakan' && $tanggal->lessThan($today)) {
            $kegiatan->update(['status' => 'selesai']);
        }
    }

    public function index()
    {
        // $kegiatan = kegiatanModel::all();
        // return response()->json($kegiatan);
        $kegiatan = kegiatanModel::all();
        foreach ($kegiatan as $item) {
            $this->updateStatusIfNeeded($item);
        }
        return response()->json(kegiatanModel::all());
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_kegiatan' => 'required|string',
    //         'isi' => 'required|string',
    //         'tanggal' => 'required|date',
    //         'waktu_mulai' => 'nullable',
    //         'waktu_selesai' => 'nullable',
    //         'lokasi' => 'required|string',
    //         'image' => 'nullable|image|max:2048',
    //         'keterangan' => 'nullable',
            
    //     ]);

    //     $data = $request->only([
    //         'nama_kegiatan',
    //         'isi',
    //         'tanggal',
    //         'waktu_mulai',
    //         'waktu_selesai',
    //         'lokasi',
    //         'keterangan',
    //     ]);

    //     // Simpan gambar jika ada
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('kegiatan', 'public');
    //         $data['image'] = $imagePath;
    //     }

    //     // Hitung status otomatis
    //     $today = Carbon::now()->startOfDay();
    //     $tanggal = Carbon::parse($data['tanggal'])->startOfDay();

    //     if ($tanggal->greaterThan($today)) {
    //         $data['status'] = 'dijadwalkan';
    //     } elseif ($tanggal->equalTo($today)) {
    //         $data['status'] = 'dilaksanakan';
    //     } else {
    //         $data['status'] = 'selesai';
    //     }

    //     $kegiatan = kegiatanModel::create($data);
    //     return response()->json($kegiatan, 201);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string',
            'isi' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:' . Carbon::now()->toDateString(),
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable|after:waktu_mulai',
            'lokasi' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'keterangan' => 'nullable',
        ]);

        $today = Carbon::now()->startOfDay();
        $tanggal = Carbon::parse($request->tanggal)->startOfDay();

        if ($tanggal->lessThan($today)) {
            return response()->json(['message' => 'Tanggal kegiatan tidak boleh di masa lalu'], 422);
        }

        $status = match (true) {
            $tanggal->greaterThan($today) => 'dijadwalkan',
            $tanggal->equalTo($today) => 'dilaksanakan',
            default => 'selesai'
        };

        $data = $request->only([
            'nama_kegiatan', 'isi', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'keterangan'
        ]);
        $data['status'] = $status;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('kegiatan', 'public');
        }

        $kegiatan = kegiatanModel::create($data);
        return response()->json($kegiatan, 201);
    }

    public function show($id)
    {
        // $kegiatan = kegiatanModel::findOrFail($id);
        // return response()->json($kegiatan);
        $kegiatan = kegiatanModel::findOrFail($id);
        $this->updateStatusIfNeeded($kegiatan);
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
            'waktu_selesai' => 'nullable|after:waktu_mulai',
            'lokasi' => 'required|string',
            'status' => 'required|string|in:dijadwalkan,dilaksanakan,selesai,dibatalkan',
            'image' => 'nullable|image|max:2048',
            'keterangan' => 'nullable',
        ]);

        $today = Carbon::now()->startOfDay();
        $tanggalBaru = Carbon::parse($request->tanggal)->startOfDay();
        $tanggalLama = Carbon::parse($kegiatan->tanggal)->startOfDay();

        if ($tanggalBaru->lessThan($today)) {
            return response()->json(['message' => 'Tanggal kegiatan tidak boleh diubah ke masa lalu'], 422);
        }

        if ($tanggalLama->lessThan($today) && in_array($request->status, ['dijadwalkan', 'dilaksanakan'])) {
            return response()->json(['message' => 'Status kegiatan yang sudah selesai tidak boleh diubah ke dijadwalkan/dilaksanakan'], 422);
        }

        $data = $request->only([
            'nama_kegiatan', 'isi', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'status', 'keterangan'
        ]);

        if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
                Storage::disk('public')->delete($kegiatan->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
                Storage::disk('public')->delete($kegiatan->image);
            }
            $data['image'] = $request->file('image')->store('kegiatan', 'public');
        }

        $kegiatan->update($data);
        return response()->json($kegiatan);
    }

    // public function update(Request $request, $id)
    // {
    //     $kegiatan = kegiatanModel::findOrFail($id);

    //     $request->validate([
    //         'nama_kegiatan' => 'required|string',
    //         'isi' => 'required|string',
    //         'tanggal' => 'required|date',
    //         'waktu_mulai' => 'nullable',
    //         'waktu_selesai' => 'nullable',
    //         'lokasi' => 'required|string',
    //         'status' => 'required|string|in:dijadwalkan,dilaksanakan,selesai,dibatalkan', // validasi status
    //         'image' => 'nullable|image|max:2048',
    //         'keterangan' => 'nullable',
    //     ]);

    //     // $data = $request->only([
    //     //     'nama_kegiatan',
    //     //     'isi',
    //     //     'tanggal',
    //     //     'waktu_mulai',
    //     //     'waktu_selesai',
    //     //     'lokasi',
    //     // ]);

    //     $data = [
    //         'nama_kegiatan' => $request->nama_kegiatan,
    //         'isi' => $request->isi,
    //         'tanggal' => $request->tanggal,
    //         'waktu_mulai' => $request->waktu_mulai,
    //         'waktu_selesai' => $request->waktu_selesai,
    //         'lokasi' => $request->lokasi,
    //         'keterangan' => $request->keterangan,
    //     ];

    //     // // Simpan gambar baru jika ada
    //     // if ($request->hasFile('image')) {
    //     //     // Hapus gambar lama
    //     //     if ($kegiatan->image) {
    //     //         Storage::disk('public')->delete($kegiatan->image);
    //     //     }

    //     //     $imagePath = $request->file('image')->store('kegiatan', 'public');
    //     //     $data['image'] = $imagePath;
    //     // }

    //     // Hapus gambar jika ada flag remove_image dan tidak upload gambar baru
    //     // if ($request->has('remove_image') && !$request->hasFile('image')) {
    //     //     if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
    //     //         Storage::disk('public')->delete($kegiatan->image);
    //     //     }
    //     //     $data['image'] = null;
    //     // }

    //     if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
    //         if ($kegiatan->image && Storage::exists('public/' . $kegiatan->image)) {
    //             Storage::delete('public/' . $kegiatan->image);
    //         }
    //         $kegiatan->image = null;
    //     }

    //     // Jika upload gambar baru
    //     if ($request->hasFile('image')) {
    //         if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
    //             Storage::disk('public')->delete($kegiatan->image);
    //         }

    //         $data['image'] = $request->file('image')->store('kegiatan', 'public');
    //     }

    //     // Update status otomatis
    //     // $today = Carbon::now()->startOfDay();
    //     // $tanggal = Carbon::parse($data['tanggal'])->startOfDay();

    //     // if ($tanggal->greaterThan($today)) {
    //     //     $data['status'] = 'dijadwalkan';
    //     // } elseif ($tanggal->equalTo($today)) {
    //     //     $data['status'] = 'dilaksanakan';
    //     // } else {
    //     //     $data['status'] = 'selesai';
    //     // }

    //     if ($request->has('status')) {
    //     $data['status'] = $request->status; // pakai status dari frontend
    //     } else {
    //     // fallback jika tidak ada status, set otomatis
    //     $today = Carbon::now()->startOfDay();
    //     $tanggal = Carbon::parse($data['tanggal'])->startOfDay();

    //     if ($tanggal->greaterThan($today)) {
    //         $data['status'] = 'dijadwalkan';
    //     } elseif ($tanggal->equalTo($today)) {
    //         $data['status'] = 'dilaksanakan';
    //     } else {
    //         $data['status'] = 'selesai';
    //     }
    // }

    //     $kegiatan->update($data);
    //     return response()->json($kegiatan);
    // }

    public function destroy($id)
    {
        $kegiatan = kegiatanModel::findOrFail($id);

        if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
            Storage::disk('public')->delete($kegiatan->image);
        }

        $kegiatan->delete();

        return response()->json([
            'message' => 'Kegiatan berhasil dihapus.'
        ]);
    }

}
