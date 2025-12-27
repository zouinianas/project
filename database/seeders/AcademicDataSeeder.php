<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;
use App\Models\Destination; // <-- 1. Importer les modèles
use App\Models\Filiere;
use App\Models\Personnel;
use App\Enums\NiveauCycle;
use Illuminate\Support\Facades\Log;

class AcademicDataSeeder extends Seeder
{
    /**
     * Exécute les seeds de la base de données.
     */
    public function run(): void
    {
        // -----------------------------------------------------------------
        // ÉTAPE 1 : CRÉER LES DONNÉES DE BASE (Départements & Destinations)
        // firstOrCreate() s'assure qu'ils ne sont créés qu'une seule fois.
        // -----------------------------------------------------------------

        $this->command->info('Création des 6 départements...');
        $depts = [
            'Géologie' => Departement::firstOrCreate(['nom' => 'Géologie'], ['couleur' => '#FFFF00']),
            'Biologie' => Departement::firstOrCreate(['nom' => 'Biologie'], ['couleur' => '#98FB98']),
            'Informatique' => Departement::firstOrCreate(['nom' => 'Informatique'], ['couleur' => '#D93275']),
            'CHIMIE' => Departement::firstOrCreate(['nom' => 'CHIMIE'], ['couleur' => '#20B2AA']),
            'Physique' => Departement::firstOrCreate(['nom' => 'Physique'], ['couleur' => '#3498DB']),
            'MATHS' => Departement::firstOrCreate(['nom' => 'MATHS'], ['couleur' => '#B729A0']),
        ];

        $this->command->info('Création des 11 destinations...');
        $destinations = [
            'Azrou', 'Adarouch', 'Ain Maatouf', 'Taounate', 'Bkerit / Timahdit',
            'Tazeka', 'M\'rirt', 'Fès', 'Massassa', 'Imouzer Kander', 'Taounate Tamaddite'
        ];
        foreach ($destinations as $dest) {
            Destination::firstOrCreate(['nom' => $dest]);
        }

        // (Optionnel) Créer quelques enseignants pour les tests
        $this->command->info('Création du personnel de test...');
        Personnel::firstOrCreate(['nom' => 'Y.Mabrouki'], ['grade' => 'Pr', 'departement_id' => $depts['Biologie']->id]);
        Personnel::firstOrCreate(['nom' => 'Benabou'], ['grade' => 'Pr', 'departement_id' => $depts['Géologie']->id]);
        Personnel::firstOrCreate(['nom' => 'N.CHMAIBI'], ['grade' => 'Pr', 'departement_id' => $depts['Physique']->id]);
        Personnel::firstOrCreate(['nom' => 'Ntarmouchant'], ['grade' => 'Pr', 'departement_id' => $depts['Géologie']->id]);


        // -----------------------------------------------------------------
        // ÉTAPE 2 : LOGIQUE "INTELLIGENTE" DE MAPPING
        // -----------------------------------------------------------------
        $this->command->info('Configuration de la logique de mapping...');
        $findDeptId = function($filiere) use ($depts) {

            // Informatique
            if (str_contains($filiere, 'MI-Informatique') || str_contains($filiere, 'IA') || str_contains($filiere, 'SMI') || str_contains($filiere, 'MQL') || str_contains($filiere, 'WISD') || str_contains($filiere, 'MLAIM') || str_contains($filiere, 'BDSI') || str_contains($filiere, '2ESI')) {
                return $depts['Informatique']->id;
            }
            // Mathématiques
            if (str_contains($filiere, 'MI-Mathématique') || str_contains($filiere, 'SMA') || str_contains($filiere, 'MASI') || str_contains($filiere, 'MMP') || str_contains($filiere, 'M2SD')) {
                return $depts['MATHS']->id;
            }
            // Physique
            if (str_contains($filiere, 'PC-Physique') || str_contains($filiere, 'SMP') || str_contains($filiere, 'M2A') || str_contains($filiere, 'PNOMER') || str_contains($filiere, 'M2SI') || str_contains($filiere, 'MAER')) {
                return $depts['Physique']->id;
            }
            // Chimie
            if (str_contains($filiere, 'PC-Chimie') || str_contains($filiere, 'SMC') || str_contains($filiere, 'CAE') || str_contains($filiere, 'CMI')) {
                return $depts['CHIMIE']->id;
            }
            // Biologie
            if (str_contains($filiere, 'BG-Biologie') || str_contains($filiere, 'SVI') || str_contains($filiere, 'BIOMSSI') || str_contains($filiere, 'BIAM') || str_contains($filiere, 'BEVP') || str_contains($filiere, 'BAEMQ')) {
                return $depts['Biologie']->id;
            }
            // Géologie
            if (str_contains($filiere, 'BG-Géologie') || str_contains($filiere, 'STU')) {
                return $depts['Géologie']->id;
            }

            // Fallbacks pour les filières courtes (DEUG S1/S2)
            if ($filiere === 'PC') return $depts['Physique']->id;
            if ($filiere === 'MI') return $depts['Informatique']->id;
            if ($filiere === 'BG') return $depts['Biologie']->id; // BG simple -> Biologie par défaut
            if ($filiere === 'IA') return $depts['Informatique']->id;

            Log::warning("Impossible de mapper la filière '$filiere'. Assignation par défaut à Informatique.");
            return $depts['Informatique']->id;
        };

        // -----------------------------------------------------------------
        // ÉTAPE 3 : CRÉER LES CYCLES (selon votre liste)
        // -----------------------------------------------------------------
        $deug_s3_s4 = ['BG-Biologie', 'BG-Géologie', 'MI-Mathématique', 'MI-Informatique', 'PC-Physique', 'PC-Chimie', 'IA'];
        $licence_s5_s6 = [
            'PC-Physique-TSE', 'PC-Physique-SER', 'PC-Physique-GMERS', 'PC-Chimie',
            'MI-Mathématique', 'MI-Informatique', 'IA',
            'BG-Géologie', 'BG-Biologie',
        ];
        $masters = [
            'BIOMSSI', 'BIAM', 'MASI', 'BEVP', 'MMP', 'M2A', 'BAEMQ',
            'PNOMER', 'MQL', 'M2SI', 'MAER', 'CAE', 'CMI', '2ESI',
            'M2SD', 'WISD', 'MLAIM', 'BDSI'
        ];

        // --- CORRECTION POUR DEUG S1/S2 ---
        $this->command->info('Création des filières DEUG S1/S2 (corrigé)...');
        foreach (['S1', 'S2'] as $semestre) {
            // Gérer PC (2 filières)
            Filiere::firstOrCreate(['nom' => "PC - $semestre (Physique)", 'niveau' => NiveauCycle::DEUG_A1], ['departement_id' => $depts['Physique']->id]);
            Filiere::firstOrCreate(['nom' => "PC - $semestre (Chimie)", 'niveau' => NiveauCycle::DEUG_A1], ['departement_id' => $depts['CHIMIE']->id]);

            // Gérer BG (2 filières)
            Filiere::firstOrCreate(['nom' => "BG - $semestre (Biologie)", 'niveau' => NiveauCycle::DEUG_A1], ['departement_id' => $depts['Biologie']->id]);
            Filiere::firstOrCreate(['nom' => "BG - $semestre (Géologie)", 'niveau' => NiveauCycle::DEUG_A1], ['departement_id' => $depts['Géologie']->id]);

            // Gérer les cas simples (MI et IA)
            Filiere::firstOrCreate(['nom' => "MI - $semestre", 'niveau' => NiveauCycle::DEUG_A1], ['departement_id' => $depts['Informatique']->id]);
            Filiere::firstOrCreate(['nom' => "IA - $semestre", 'niveau' => NiveauCycle::DEUG_A1], ['departement_id' => $depts['Informatique']->id]);
        }

        // --- S3/S4 ---
        $this->command->info('Création des filières DEUG S3/S4...');
        foreach (['S3', 'S4'] as $semestre) {
            foreach ($deug_s3_s4 as $filiere) {
                Filiere::firstOrCreate(['nom' => "$filiere - $semestre", 'niveau' => NiveauCycle::DEUG_A2], ['departement_id' => $findDeptId($filiere)]);
            }
        }

        // --- Licence S5/S6 ---
        $this->command->info('Création des filières Licence S5/S6...');
        foreach (['S5', 'S6'] as $semestre) {
            foreach ($licence_s5_s6 as $filiere) {
                Filiere::firstOrCreate(['nom' => "$filiere - $semestre", 'niveau' => NiveauCycle::Licence], ['departement_id' => $findDeptId($filiere)]);
            }
        }

        // --- Master S1/S2/S3 ---
        $this->command->info('Création des filières Master S1/S2/S3/S4...');
        foreach (['S1', 'S2', 'S3', 'S4'] as $semestre) {
            foreach ($masters as $filiere) {
                Filiere::firstOrCreate(['nom' => "$filiere - $semestre", 'niveau' => NiveauCycle::Master], ['departement_id' => $findDeptId($filiere)]);
            }
        }

        $this->command->info('Toutes les données de base ont été créées.');
    }
}
