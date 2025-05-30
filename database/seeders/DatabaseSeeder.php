<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\userModel;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // userModel::factory(10)->create();

        $this->call([
        roleSeeder::class,
        userSeeder::class,
        jenisTransaksiSeeder::class,
        transaksiSeeder::class,
        informasiSeeder::class,
        kegiatanSeeder::class,
        reservasiSeeder::class,
        // tambahkan seeder lainnya di sini
    ]);
    }
}
