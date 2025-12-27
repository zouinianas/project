<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Filiere;

class ModulesS2Seeder extends Seeder
{
    public function run(): void
    {
        $modulesS2 = [
            'Electrostatique Et Électrocinétique',
            'Optique Géométrique',
            'Liaisons Chimiques',
            'Chimie Des Solutions',
            'Analyse 2',
            'Algèbre 2',
            'Langue Et Terminologie II',
        ];

        $filieresS2 = [
            'PC - S2 (Physique)',
            'PC - S2 (Chimie)',
            'BG - S2 (Biologie)',
            'BG - S2 (Géologie)',
            'MI - S2',
            'IA - S2',
        ];

        foreach ($filieresS2 as $filiereName) {

            $filiere = Filiere::where('nom', $filiereName)->first();

            if (!$filiere) {
                continue; // sécurité
            }

            foreach ($modulesS2 as $module) {
                Module::firstOrCreate([
                    'nom' => $module,
                    'filiere_id' => $filiere->id,
                ]);
            }
        }
    }
}
