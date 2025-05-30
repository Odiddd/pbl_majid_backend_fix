<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['role_id' => 1, 'role_name' => 'SuperAdmin'],
            ['role_id' => 2, 'role_name' => 'Admin'],
        ];
        DB::table('role')->insert($data);
    }
}
