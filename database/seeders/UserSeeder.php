<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'type' => 'admin',
                'bio' =>'administrator',
                'photo' => null,
                'password' => Hash::make('password'),
                'updated_at' => new DateTime(),
                'isAdmin' => 1,
           
            ],
            [
                'name' => 'tech',
                'email' => 'technical@gmail.com',
                'username' => 'tech',
                'type' => 'tech',
                'bio' =>'technical department',
                'photo' => null,
                'password' => Hash::make('password'),
                'updated_at' => new DateTime(),
                'isAdmin' => 1
            ]
        ]);
    }
}
