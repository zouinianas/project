<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Filiere;

class ModulesS1S3S5Seeder extends Seeder
{
    public function run(): void
    {
        $data = [

            /* ===================== S1 ===================== */
            'S1' => [

                'BG' => [
                    'MTU',
                    'Biologie cellulaire',
                    "Histologie et notions d'embryologie",
                    'Géologie générale',
                    'Atomistique et liaison chimique',
                    'Thermodynamique / Mécanique',
                    'Mathématique',
                ],

                'PC' => [
                    'MTU',
                    'Algèbre 1',
                    'Analyse 1',
                    'Atomistique',
                    'Thermochimie',
                    'Thermodynamique',
                    'Mécanique du Point',
                ],

                'MI' => [
                    'MTU',
                    'Algèbre 1',
                    'Algèbre 2',
                    'Analyse 1',
                    'Thermodynamique',
                    'Mécanique du point',
                    'Informatique 1',
                ],

                'IA' => [
                    'Analyse 1',
                    'Algèbre 1',
                    'Électronique Numérique',
                    'Algorithmique et Programmation C 1',
                    'Programmation Python 1',
                    'Méthodologie De Travail Universitaire',
                    'Architecture Et Fonctionnement Des Ordinateurs',
                ],
            ],

            /* ===================== S3 ===================== */
            'S3' => [

                'BG-Biologie' => [
                    'Langues',
                    'Biochimie structurale',
                    'Microbiologie générale',
                    'Ecologie générale',
                    "Techniques d'analyse",
                    'Biostatistiques',
                    'Informatique pour la biologie',
                ],

                'BG-Géologie' => [
                    'Langues',
                    'Tectonique Analytique',
                    'Tectonique globale',
                    'Pétrologie magmatique',
                    'Pétrologie métamorphique',
                    'Pétrographie sédimentaire',
                    'Hydrogéologie et hydrologie',
                ],

                'IA' => [
                    'Modélisation Objet UML',
                    'Probabilités et Statistiques',
                    'Programmation Web 2',
                    'Langues Étrangères',
                    'Structure De Données En C',
                    "Système D’Exploitation 1",
                    'Recherche Opérationnelle Et Optimisation',
                ],

                'MI-Informatique' => [
                    'Langues',
                    'Programmation Objet UML',
                    'Programmation web 1',
                    'Programmation en langage C',
                    "Système d'exploitation 1",
                    'Architecture des ordinateurs',
                    'Probabilités et statistiques',
                ],

                'MI-Mathématique' => [
                    'Langues',
                    'Analyse 4',
                    'Analyse 5',
                    'Algèbre 4',
                    'Probabilités et statistiques',
                    'Informatique 3',
                    'Mécanique du solide',
                ],

                'PC-Chimie' => [
                    'Langues',
                    'Chimie descriptive I - diagramme des phases',
                    'Chimie organique générale',
                    'Chimie des électrolytes',
                    'Mathématiques - Chimie',
                    'Electromagnétisme',
                    'Algorithmique & programmation python',
                ],

                'PC-Physique' => [
                    'Langues',
                    'Mécanique du solide',
                    'Circuits électriques',
                    'Electromagnétisme',
                    'Chimie organique générale',
                    'Thermodynamique 2',
                    'Mathématiques - Physique',
                ],
            ],

            /* ===================== S5 ===================== */
            'S5' => [

                'BG-Biologie' => [
                    'Anglais',
                    'Génétique II',
                    'Taxonomie animale et végétale',
                    'Croissance Et Dévelopement Des Plantes',
                    'Physiologie Humaine',
                    'Écologie générale II',
                    'Immunologie',
                ],

                'BG-Géologie' => [
                    'Anglais',
                    'Géologie Du Maroc',
                    'Digital Skills II: Excel Avancé',
                    'École De Terrain',
                    'Géophysique Appliquée',
                    'Gîtologie Et Prospection Minière',
                    'Dynamique De La Lithosphère Et Croissance Crustale',
                ],

                'IA' => [
                    'Anglais',
                    'Technologie Java',
                    'Théorie Des Langages Et Compilation',
                    'Technologie XML',
                    'Bases de données (PL SQL)',
                    'Robotique et système embarqués',
                    'Gestion De Projets',
                ],

                'MI-GI' => [
                    'Anglais',
                    'Technologie XML',
                    'Robotique et Systèmes Embarqués',
                    'Théorie Des Langages Et Compilation',
                    'Programmation Java',
                    'Recherche Opérationnelle & Théorie De Graphes',
                    'Réseaux Informatiques',
                ],

                'MI-M' => [
                    'Anglais',
                    'Soft Skills',
                    'Intégration 1',
                    'Topologie 1',
                    'Calcul différentiel',
                    'Résolution numérique des équations',
                    'Analyse des données',
                ],

                'PC-C' => [
                    'Anglais',
                    'Modélisation Moléculaire',
                    'Les Grandes Classes De Réactions Organiques',
                    'Radiocristallographie Et Cristallochimie II',
                    'Digital Skills II: Excel Avancé',
                    'Cinétique & Catalyse',
                    'Cinétique Électrochimique',
                ],

                'PC-GMERS' => [
                    'Anglais',
                    'Systèmes De Gestion De Contenu (CMS)',
                    'Physique Statistique',
                    'Technologie Des Batteries',
                    'Physique Des Matériaux',
                    "Transfert Et Stockage De L'Énergie Thermique",
                    'Mécanique Quantique',
                ],

                'PC-SER' => [
                    'Anglais',
                    'Capteurs Et Instrumentation',
                    'Systèmes De Gestion De Contenu (CMS)',
                    'Transfert Thermique Et Énergie Solaire',
                    'Électrotechnique & Électronique De Puissance',
                    'Asservissement Et Régulation Industrielle',
                    'Électronique Analogique II',
                ],

                'PC-TSE' => [
                    'Anglais',
                    'Capteurs Et Instrumentation',
                    'Systèmes De Gestion De Contenu (CMS)',
                    'Asservissement Des Systèmes Linéaires Continus',
                    'Traitement De Signal',
                    'Informatique Avancée',
                    'Circuits Analogiques',
                ],
            ],
        ];

        foreach ($data as $semestre => $filieres) {
            foreach ($filieres as $key => $modules) {

                $filiere = Filiere::where('nom', 'LIKE', "%$key%")
                    ->where('nom', 'LIKE', "%$semestre%")
                    ->first();

                if (!$filiere) {
                    continue;
                }

                foreach ($modules as $module) {
                    Module::firstOrCreate([
                        'nom' => $module,
                        'filiere_id' => $filiere->id,
                    ]);
                }
            }
        }
    }
}
