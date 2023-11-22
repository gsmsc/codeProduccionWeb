<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
       $role = Role::create(['name' => 'Super Admin']);

        $user = User::where('email', 'jluna@gruposistecom.mx')->first();
        $user->assignRole('Super Admin');
    }
}
