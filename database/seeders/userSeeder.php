<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1, 
                'role_id' => 1,
                'name' => 'yonatan',
                'email' => 'yonadmin@gmail.com',
                'password' => 'simasjid123',
            ],
            [
                'user_id' => 2, 
                'role_id' => 2,
                'name' => 'admin1',
                'email' => 'admin1@gmail.com',
                'password' => 'abcd1234',
            ],
            [
                'user_id' => 3, 
                'role_id' => 2,
                'name' => 'admin2',
                'email' => 'admin2@gmail.com',
                'password' => '1234abc',
            ],
        
        ];
        DB::table('users')->insert($data);
    }
}
