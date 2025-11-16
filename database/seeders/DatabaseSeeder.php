<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aqui você registra todos os seeders que quer rodar
        $this->call([
            UserSeeder::class,
        ]);
    }
}
