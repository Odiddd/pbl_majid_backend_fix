<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\reservasiModel;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReservasiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Pastikan ada tempat_reservasi dengan ID yang valid (misal ID = 1)
        DB::table('tempat_reservasi')->insertOrIgnore([
    'tempat_reservasi_id' => 1,
    'lokasi' => 'Aula Utama - Jl. Merdeka No. 1',
    'kapasitas' => 100,
    'image' => null,
    'keterangan' => 'Tempat serbaguna untuk acara besar.',
    'biaya' => 1000000,
    'created_at' => now(),
    'updated_at' => now(),
]);


        // Buat 10 data dummy untuk reservasi
        for ($i = 0; $i < 10; $i++) {
            reservasiModel::create([
                'nama_pemesan' => $faker->name,
                'kontak_pemesan' => $faker->phoneNumber,
                'tempat_reservasi_id' => 1, // pastikan tempat_reservasi_id = 1 ada
                'nama_acara' => $faker->sentence(3),
                'tanggal_acara' => $faker->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
                'waktu_mulai' => $faker->time('H:i:s'),
                'waktu_selesai' => $faker->time('H:i:s'),
                'jumlah_tamu' => $faker->numberBetween(10, 100),
                'status_reservasi' => $faker->randomElement(['menunggu', 'dikonfirmasi', 'dijadwalkan', 'dilaksanakan', 'selesai', 'batal']),
                'mengetahui' => $faker->name,
                'tagihan' => $faker->randomFloat(2, 100000, 5000000),
                'status_pembayaran' => $faker->randomElement(['pending', 'success', 'failed']),
                'keterangan' => $faker->sentence(),
                'masuk_transaksi' => $faker->boolean(),
            ]);
        }
    }
}
