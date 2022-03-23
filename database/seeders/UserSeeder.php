<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Gilmar',
            'email' => 'gilmar@tecnologiadc.com.br',
            'cell' => '(81)98300-0093',
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Danilo',
            'email' => 'danilo@tecnologiadc.com.br',
            'cell' => '(11)90000-0000',
            'password' => Hash::make('one'),
            'created_at' => now(),
        ]);
    }
}
