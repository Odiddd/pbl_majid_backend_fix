<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TempatReservasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tempat_reservasi')->insertOrIgnore([
            [
                'tempat_reservasi_id' => 1,
                'lokasi' => 'Aula Utama',
                'kapasitas' => 150,
                'image' => 'aula.jpg',
                'keterangan' => 'Aula besar untuk acara pernikahan dan seminar.',
                'biaya' => 2000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
