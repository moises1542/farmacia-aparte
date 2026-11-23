<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => Role::ADMIN],
            ['description' => 'Acceso total al sistema'],
        );

        Role::firstOrCreate(
            ['name' => 'Farmacéutico'],
            ['description' => 'Registra ventas y gestiona medicamentos y clientes'],
        );
    }
}
