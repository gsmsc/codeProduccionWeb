<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create(
            [
                'name' => 'Jesus Luna',
                'email' => 'jluna@gruposistecom.mx',
                'password' => Hash::make('gsmeg4'),
                'idRol' => 1,
            ]
        );
    }
}
