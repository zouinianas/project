<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'BG-Biologie - S3' => [
                'Entomologie',
                'Ecologie Générale 2',
                'BioTechnologie',
            ],

            'BG-Géologie - S3' => [
                'Pétro Magmatique',
                'Pétro Métamorphique',
                'Tectonique Analytique',
                'Tectonique Globale',
            ],

            'PC-Physique - S5' => [
                'Energie Verte',
                'Géologie du Maroc',
            ],
        ];

        foreach ($data as $filiereNom => $modules) {

            $filiere = Filiere::where('nom', $filiereNom)->first();

            if (!$filiere) {
                $this->command->warn("❌ Filière introuvable : $filiereNom");
                continue;
            }

            foreach ($modules as $moduleNom) {
                Module::firstOrCreate([
                    'nom' => $moduleNom,
                    'filiere_id' => $filiere->id,
                ]);
            }
        }
    }
}
