<?php

namespace Database\Seeders;

use App\Models\kegiatanModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class kegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            kegiatanModel::create([
                'nama_kegiatan'   => 'Kegiatan RT ' . $i,
                'isi'             => 'Ini adalah deskripsi lengkap kegiatan ke-' . $i,
                'tanggal'         => Carbon::now()->addDays($i),
                'waktu_mulai'     => '08:00:00',
                'waktu_selesai'   => '11:00:00',
                'lokasi'          => 'Balai RW 05',
                'status'          => ['dijadwalkan', 'dilaksanakan', 'selesai', 'dibatalkan'][rand(0, 3)],
                'image'           => null,
                'keterangan'      => 'Keterangan tambahan untuk kegiatan ke-' . $i,
            ]);
        }
    }
}
