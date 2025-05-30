<?php

namespace Database\Seeders;

use App\Models\informasiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class informasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            informasiModel::create([
                'judul'      => 'Pengumuman Penting ke-' . $i,
                'isi'        => 'Ini adalah isi lengkap dari informasi ke-' . $i . '. Berisi detail penting yang perlu diketahui warga.',
                'status'     => $i % 2 === 0 ? 'aktif' : 'arsip',
                'image'      => null,
                'keterangan' => 'Keterangan tambahan untuk informasi ke-' . $i,
                'created_at' => Carbon::now()->subDays($i),
                'updated_at' => Carbon::now()->subDays($i),
            ]);
        }
    }
}
