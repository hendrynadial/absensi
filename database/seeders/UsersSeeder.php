<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [ 
                'name' => 'Admin',
                'username' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'profile' => 'Admin',
                'status' => "Terverifikasi",
            ]
        ];
        DB::table('users')->insert($users);
    }
}
