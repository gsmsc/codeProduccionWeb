<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionAdminSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'SuperAdmin.ListadoSupervisores']);
        Permission::create(['name' => 'SuperAdmin.VerActividad']);
        Permission::create(['name' => 'SuperAdmin.ReporteXLSXSupervisor']);
        Permission::create(['name' => 'SuperAdmin.FiltradoFecha']);
        Permission::create(['name' => 'SuperAdmin.VerFormatoPDF']);
    }
}
