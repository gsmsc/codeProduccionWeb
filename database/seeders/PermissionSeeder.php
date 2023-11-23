<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'Lineas.Index']);
        Permission::create(['name' => 'Lineas.Crear']);
        Permission::create(['name' => 'Lineas.Editar']);
        Permission::create(['name' => 'Lineas.Eliminar']);

        Permission::create(['name' => 'Estilos.Index']);
        Permission::create(['name' => 'Estilos.Crear']);
        Permission::create(['name' => 'Estilos.Editar']);
        Permission::create(['name' => 'Estilos.Eliminar']);

        Permission::create(['name' => 'Produccion.Index']);
        Permission::create(['name' => 'Produccion.Crear']);
        Permission::create(['name' => 'Produccion.Editar']);
        Permission::create(['name' => 'Produccion.Eliminar']);
        Permission::create(['name' => 'Produccion.ReporteExcel']);
        Permission::create(['name' => 'Produccion.ReportePDF']);

        Permission::create(['name' => 'Usuarios.Index']);
        Permission::create(['name' => 'Usuarios.Crear']);
        Permission::create(['name' => 'Usuarios.Editar']);
        Permission::create(['name' => 'Usuarios.Eliminar']);

        Permission::create(['name' => 'Roles.Index']);
        Permission::create(['name' => 'Roles.Crear']);
        Permission::create(['name' => 'Roles.Editar']);
        Permission::create(['name' => 'Roles.Eliminar']);
    }
}
