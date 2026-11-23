<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::exists()) {
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@sanjuan.com'],
            [
                'role_id'  => Role::where('name', Role::ADMIN)->value('id'),
                'name'     => 'Administrador',
                'dni'      => '70000001',
                'phone'    => '987654321',
                'password' => 'admin12345',
            ],
        );

        User::firstOrCreate(
            ['email' => 'farmaceutico@sanjuan.com'],
            [
                'role_id'  => Role::where('name', 'Farmacéutico')->value('id'),
                'name'     => 'Ana Torres',
                'dni'      => '70000002',
                'phone'    => '912345678',
                'tuition'  => 'CQFP 12345',
                'password' => 'farma12345',
            ],
        );
    }
}
