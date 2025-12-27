<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /**
             * Structure :
             * code => [
             *   'intitule' => '',
             *   'semestres' => [
             *      1 => [modules],
             *      2 => [modules],
             *      3 => [modules],
             *      4 => [modules],
             *   ]
             * ]
             */

            $masters = [

                'BIAM' => [
                    'intitule' => 'Biologie Intégrative Appliquée et Moléculaire',
                    'semestres' => [
                        1 => [
                            'Biologie moléculaire avancée',
                            'Bioinformatique',
                            'Statistiques appliquées',
                            'Biologie cellulaire avancée',
                            'Anglais',
                        ],
                        2 => [
                            'Génomique',
                            'Protéomique',
                            'Méthodologie de la recherche scientifique',
                            'Techniques expérimentales avancées',
                        ],
                        3 => [
                            'Biotechnologies avancées',
                            'Bioéthique',
                            'Analyse des données biologiques',
                        ],
                        4 => [
                            'Projet de Fin d’Études',
                        ],
                    ],
                ],

                'BDSI' => [
                    'intitule' => 'Big Data et Systèmes Intelligents',
                    'semestres' => [
                        1 => [
                            'Fondements de l’Intelligence Artificielle',
                            'Python',
                            'Statistiques',
                            'Data Mining',
                        ],
                        2 => [
                            'Big Data Analytics',
                            'Cloud Computing',
                            'Cybersécurité',
                            'Méthodologie de la recherche scientifique',
                        ],
                        3 => [
                            'Deep Learning',
                            'Traitement du Langage Naturel',
                            'Blockchain',
                        ],
                        4 => [
                            'Projet de Fin d’Études',
                        ],
                    ],
                ],

                'MLAIM' => [
                    'intitule' => 'Machine Learning Avancé et Intelligence Multimédia',
                    'semestres' => [
                        1 => [
                            'Python Avancé',
                            'Traitement d’Images',
                            'Optimisation',
                            'Probabilités',
                        ],
                        2 => [
                            'Réseaux de Neurones',
                            'Big Data',
                            'Méthodologie de la recherche scientifique',
                        ],
                        3 => [
                            'Apprentissage Profond',
                            'Vision par Ordinateur',
                            'Réalité Augmentée',
                        ],
                        4 => [
                            'Projet de Fin d’Études',
                        ],
                    ],
                ],

                'WISD' => [
                    'intitule' => 'Web Intelligence et Science des Données',
                    'semestres' => [
                        1 => [
                            'Bases de Données Avancées',
                            'Programmation Web Avancée',
                            'Statistiques Multidimensionnelles',
                        ],
                        2 => [
                            'Web Mining',
                            'Recherche Opérationnelle',
                            'Cybersécurité',
                        ],
                        3 => [
                            'Machine Learning',
                            'Data Warehouse',
                            'Business Intelligence',
                        ],
                        4 => [
                            'Projet de Fin d’Études',
                        ],
                    ],
                ],

            ];

            foreach ($masters as $code => $data) {

                // 1️⃣ Création du master
                $masterId = DB::table('masters')->insertGetId([
                    'code' => $code,
                    'intitule' => $data['intitule'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 2️⃣ Liaison modules existants
                foreach ($data['semestres'] as $semestre => $modules) {
                    foreach ($modules as $nomModule) {

                        $module = DB::table('modules')
                            ->where('nom', $nomModule)
                            ->first();

                        if ($module) {
                            DB::table('master_module')->insert([
                                'master_id' => $masterId,
                                'module_id' => $module->id,
                                'semestre' => $semestre,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
