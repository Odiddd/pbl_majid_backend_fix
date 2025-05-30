<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jenisTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['jenis_transaksi_id' => 1, 'jenis_name' => 'rekening'],
            ['jenis_transaksi_id' => 2, 'jenis_name' => 'tunai'],
        ];
        DB::table('jenis_transaksi')->insert($data);
    }
}
