<?php

namespace Database\Seeders;

use App\Models\transaksiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class transaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            transaksiModel::create([
                'kategori'             => $i % 2 === 0 ? 'pemasukan' : 'pengeluaran',
                'jenis_transaksi_id'  => $i % 2 === 0 ? 1 : 2, // 1 = rekening, 2 = tunai
                'nominal'              => rand(10000, 100000),
                'sumber'               => $i % 3 === 0 ? 'Donatur ' . $i : 'hamba Allah',
                'mengetahui'           => ['admin1', 'admin2'][rand(0, 1)],
                'status'               => $i % 2 === 0 ? 'valid' : 'draft',
                'keterangan'           => 'Keterangan transaksi ke-' . $i,
                'created_at'           => Carbon::now()->subDays($i),
                'updated_at'           => Carbon::now()->subDays($i),
            ]);
        }
    }
}
