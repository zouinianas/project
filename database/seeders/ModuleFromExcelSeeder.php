<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Filiere;
use App\Models\Departement;
use Maatwebsite\Excel\Facades\Excel;

class ModuleFromExcelSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/modules/Module-25-26.xlsx');
        $rows = Excel::toArray([], $path)[0];

        unset($rows[0]); // supprimer header

        foreach ($rows as $row) {

            $moduleName  = trim($row[1] ?? '');
            $filiereRaw  = trim($row[3] ?? '');
            $semestreRaw = trim($row[4] ?? '');

            if ($moduleName === '' || $filiereRaw === '' || $semestreRaw === '') {
                continue;
            }

            // 🔁 convertir 1 → S1
            $semestre = 'S' . (int) $semestreRaw;

            // 🎯 déterminer le département
            $departement = $this->detectDepartement($filiereRaw);
            if (!$departement) {
                continue;
            }

            // 🔎 chercher la filière exacte
            $filiere = Filiere::where('departement_id', $departement->id)
                ->where('nom', 'LIKE', "%$semestre%")
                ->first();

            if (!$filiere) {
                continue;
            }

            Module::firstOrCreate([
                'nom' => $moduleName,
                'filiere_id' => $filiere->id,
            ]);
        }
    }

    private function detectDepartement(string $filiere): ?Departement
    {
        $map = [
            'Physique' => 'Physique',
            'Chimie' => 'CHIMIE',
            'Math' => 'MATHS',
            'Informatique' => 'Informatique',
            'Biologie' => 'Biologie',
            'Géologie' => 'Géologie',
            'SMC' => 'CHIMIE',
            'SMI' => 'Informatique',
            'SMA' => 'MATHS',
            'SVT' => 'Biologie',
        ];

        foreach ($map as $key => $deptName) {
            if (stripos($filiere, $key) !== false) {
                return Departement::where('nom', $deptName)->first();
            }
        }

        return null;
    }
}
