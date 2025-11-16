<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuário ADMIN
        User::updateOrCreate(
            ['email' => 'admin@consignado.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('admin123'),
                'perfil'   => 'ADMIN',
                'active'   => true,
            ]
        );

        // Usuário VENDEDOR
        User::updateOrCreate(
            ['email' => 'vendedor@consignado.com'],
            [
                'name'     => 'Vendedor Teste',
                'password' => Hash::make('vendedor123'),
                'perfil'   => 'VENDEDOR',
                'active'   => true,
            ]
        );
    }
}
