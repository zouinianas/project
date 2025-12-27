<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crée l'utilisateur de test (MAINTENANT CORRIGÉ)
        // Vous pouvez changer les valeurs si vous le souhaitez


$this->call([
    PersonnelSeeder::class,
]);
    }
}
