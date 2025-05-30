<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class tempatReservasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'lokasi' => 'Aula Utama Masjid',
                'kapasitas' => 300,
                'image' => 'aula_utama.jpg',
                'keterangan' => 'Digunakan untuk pernikahan, kajian umum, atau seminar besar.',
                'biaya' => 5000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lokasi' => 'Ruang Serbaguna Lantai 2',
                'kapasitas' => 100,
                'image' => 'serbaguna_l2.jpg',
                'keterangan' => 'Cocok untuk rapat komunitas, pelatihan, atau pengajian kelompok kecil.',
                'biaya' => 2000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lokasi' => 'Halaman Belakang Masjid',
                'kapasitas' => 500,
                'image' => 'halaman_belakang.jpg',
                'keterangan' => 'Area terbuka, cocok untuk kegiatan bazar, festival, atau olahraga.',
                'biaya' => 1000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lokasi' => 'Ruang Kelas TPQ A',
                'kapasitas' => 30,
                'image' => 'tpq_a.jpg',
                'keterangan' => 'Ruang kelas untuk TPQ, dapat digunakan untuk pelatihan atau belajar kelompok.',
                'biaya' => 500000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lokasi' => 'Area Parkir Barat',
                'kapasitas' => 100,
                'image' => 'parkir_barat.jpg',
                'keterangan' => 'Parkir tambahan untuk acara besar di masjid.',
                'biaya' => 750000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lokasi' => 'Gazebo Taman Masjid',
                'kapasitas' => 20,
                'image' => 'gazebo.jpg',
                'keterangan' => 'Tempat santai terbuka, cocok untuk mentoring atau diskusi keagamaan.',
                'biaya' => 300000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('tempat_reservasi')->insert($data);
    }
}
