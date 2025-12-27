<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Filiere;

class MasterModulesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            /* ==========================================================
               SEMESTRE 1 (Master)
               ========================================================== */
            'S1' => [
                '2ESI' => [
                    'HIGH FREQUENCY DESIGN TECHNIQUES (*)',
                    'COMMUNICATIONS NUMÉRIQUES AVANCÉES',
                    'CAPTEURS INTELLIGENTS EMBARQUÉS',
                    'TECHNOLOGIES DES CIRCUITS INTÉGRÉS',
                    'CIRCUITS ÉLECTRONIQUES AVANCÉS',
                    'INTELLIGENCE ARTIFICIELLE',
                    'ANGLAIS',
                ],
                'WISD' => [
                    'PROGRAMMATION AVANCÉE ET TECHNOLOGIE JEE',
                    'ECHANGE DE DONNÉES ET INTEROPÉRABILITÉ (*)',
                    'BASES DE DONNÉES AVANCÉES',
                    'STATISTIQUES EXPLORATOIRES MULTIDIMENSIONNELLES',
                    'INTELLIGENCE ARTIFICIELLE',
                    'DIGITAL SKILLS',
                    'ANGLAIS',
                ],
                'MLAIM' => [
                    'PYTHON AVANCÉ',
                    'BASES DE DONNÉES AVANCÉES',
                    "TRAITEMENT D'IMAGES",
                    'INFOGRAPHIE',
                    'OPTIMISATION ET PROGRAMMATION PAR CONTRAINTES',
                    'PROBABILITÉ ET PROCESSUS STOCHASTIQUE',
                    'ANGLAIS',
                ],
                'BDSI' => [
                    "FONDEMENTS DE L'INTELLIGENCE ARTIFICIELLE / PYTHON",
                    'VISION PAR ORDINATEUR',
                    'DATA MINING',
                    'STATISTIQUES / STOCHASTIQUES',
                    'ARCHITECTURE DES SYSTÈMES DE DONNÉES',
                    "THÉORIE DES GRAPHES POUR L’ANALYSE DES GRANDS RÉSEAUX (*)",
                    'ANGLAIS',
                ],
                'BAEMQ' => [ // Nom corrigé selon votre dump SQL (BAEMQ)
                    'NUTRIGÉNOMIQUE ET MICROBIOME INTESTINAL (*)',
                    'TOXICOLOGIE ALIMENTAIRE',
                    'TECHNIQUES D’ANALYSE DES ALIMENTS',
                    'RECHERCHE ET DÉVELOPPEMENT DES BIOMOLÉCULES D’INTÉRÊT',
                    'BIOSTATISTIQUE ET ANALYSES DES DONNÉES',
                    'INTELLIGENCE ARTIFICIELLE',
                    'ANGLAIS',
                ],
                'BEVP' => [
                    'ÉCOLOGIE DES ÉCOSYSTÈMES VÉGÉTAUX DANS LES RÉGIONS MÉDITERRANÉENNES',
                    'BIOSTATISTIQUES - ANALYSE DES DONNÉES',
                    'BIOLOGIE DE LA REPRODUCTION ET CONSERVATION DES VÉGÉTAUX SUPÉRIEURS',
                    'GÉOMORPHOLOGIE, PÉDOLOGIE ET DYNAMIQUE SÉDIMENTAIRE',
                    'ÉCOLOGIE DES EAUX DOUCES (*)',
                    'INITIATION A L’IA',
                    'ANGLAIS',
                ],
                'BIAM' => [
                    'BIG DATA I',
                    'Advanced Programmation / Python, R',
                    "Traitement d'images // Traitement automatique du langage naturel",
                    'Langues Etrangères : Anglais',
                    'Digital Skills',
                    'Statistics for Bioinformatics and Systems Biology',
                    'Molecular & Cellular Biology *',
                ],
                'BIOMSSI' => [
                    'Pharmacologie Toxicologie',
                    'Immunologie appliquée',
                    'Santé digitale',
                    'Génomique fonctionnelle et bio-informatique',
                    'Ecoconception et Innovation Durable des Matériaux Biomédicaux',
                    'Langues étrangère II',
                    "Initiation à l'IA et application en sciences biomédicales",
                ],
                'CAE' => [
                    "METHODES D'ANALYSES SPECTROSCOPIE: RMN; IR; UV Vis; SM",
                    'SANTE-SECURITE CHIMIQUE AU LABORATOIRE',
                    'CHIMIE DES SURFACES ET INTERFACES (*)',
                    'MODELISATION MOLECULAIRE ET APPLICATIONS',
                    'PROCEDES HYDROMETALLURGIQUES ET PYROMETALLURGIQUES',
                    'CULTURE INFORMATIQUE ET INTELLIGENCE ARTIFICIELLE',
                    'ANGLAIS',
                ],
                'M2A' => [
                    'MÉCANIQUE QUANTIQUE',
                    'TRANSITION DE PHASES ET PHYSIQUE DE STATISTIQUE HORS D’ÉQUILIBRE',
                    'RADIATION MATTER INTERACTION: HEALTH IMPACTS (*)',
                    "OUTILS SCIENTIFIQUES ET NUMÉRIQUES POUR L'ÉTUDE DES MATÉRIAUX",
                    'LES PROPRIÉTÉS ÉLECTRONIQUES ET MAGNÉTIQUES DES MATÉRIAUX',
                    'INITIATION A L’IA',
                    'ANGLAIS',
                ],
                'M2SD' => [
                    'APPRENTISSAGE AUTOMATIQUE',
                    'PROBABILITÉS APPROFONDIES',
                    'OPTIMISATION NUMÉRIQUE',
                    'ANALYSE FONCTIONNELLE ET ESPACE DE FONCTIONS',
                    'LANGAGE DE PROGRAMMATION ORIENTÉE OBJET',
                    'NUMERICAL METHODS FOR PDES (*)',
                    'ANGLAIS',
                ],
                'MAER' => [
                    'AUTOMATIQUE DE BASE',
                    'ASSERVISSEMENT ÉCHANTILLONNÉ DES SYSTÈMES LINÉAIRES',
                    'POWER ELECTRONICS (*)',
                    'TRAITEMENT NUMÉRIQUE DU SIGNAL',
                    'RECHERCHE OPÉRATIONNELLE',
                    'INTELLIGENCE ARTIFICIELLE',
                    'ANGLAIS',
                ],
                'MASI' => [
                    'MODÉLISATION MATHÉMATIQUE ET NUMÉRIQUE DES EDPs',
                    'RECHERCHE OPÉRATIONNELLE',
                    'ANALYSE FONCTIONNELLE ET ESPACE DE FONCTIONS',
                    'LANGAGE DE PROGRAMMATION ORIENTÉ OBJET',
                    'MÉTHODES NUMÉRIQUES POUR LES EDPs (*)',
                    'INITIATION A L’INTELLIGENCE ARTIFICIELLE',
                    'ANGLAIS',
                ],
                'MMP' => [
                    'THÉORIE DES MODULES SUR UN ANNEAU COMMUTATIF',
                    'THÉORIE DES CORPS COMMUTATIFS',
                    'GÉOMÉTRIE DIFFÉRENTIELLE',
                    'ANALYSE FONCTIONNELLE (*)',
                    'ANALYSE COMPLEXE',
                    'INITIATION A L’INTELLIGENCE ARTIFICIELLE',
                    'ANGLAIS',
                ],
                'PNOMER' => [
                    'PRINCIPE ET APPLICATIONS DES INTERACTIONS ONDE MATIÈRES',
                    'MODÉLISATION ET MÉTHODES MATHÉMATIQUES DE LA PHYSIQUE',
                    'MÉCANIQUE QUANTIQUE AVANCÉE',
                    'PHYSIQUE STATISTIQUE AVANCÉE',
                    'STATE CHANGES AND CRITICAL PHENOMENA IN MATERIALS',
                    'INTRODUCTION A L’IA',
                    'ANGLAIS',
                ],
            ],

            /* ==========================================================
               SEMESTRE 2 (Master)
               ========================================================== */
            'S2' => [
                '2ESI' => [
                    'CONCEPTION DES CIRCUITS INTÉGRÉS ANALOGIQUES',
                    'CONCEPTION DES CIRCUITS LOGIQUES PROGRAMMABLES',
                    'CONCEPTION DES CIRCUITS INTÉGRÉS NUMÉRIQUES',
                    'ÉLECTRONIQUE EMBARQUÉE POUR LES SYSTÈMES AUTONOMES (*)',
                    'OBJETS INTELLIGENTS ET INTERNET DES OBJETS',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'WISD' => [
                    'TRAITEMENT AUTOMATIQUE DU LANGAGE NATUREL',
                    'RECHERCHE OPÉRATIONNELLE ET OPTIMISATION',
                    'VISUAL ANALYTICS',
                    'RÉSEAUX ET CYBERSÉCURITÉ (*)',
                    'WEB MOBILE ET WEB DESIGN',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'MLAIM' => [
                    'RÉSEAUX DE NEURONES ARTIFICIELS / PROFONDS',
                    'APPRENTISSAGE AUTOMATIQUE ET FOUILLE DE DONNÉES',
                    'INFORMATIQUE DÉCISIONNELLE ET POWER BI',
                    'GOUVERNANCE DE DONNÉES ET MÉTAHEURISTIQUES',
                    'BIG DATA',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'BDSI' => [
                    'CYBERSÉCURITÉ ET CLOUD COMPUTING',
                    'INTELLIGENCE COMPUTATIONNELLE',
                    'BIG DATA ANALYTICS I / RECONNAISSANCE VOCALE',
                    'INTERNET DES OBJETS ET INFORMATIQUE EN PÉRIPHÉRIE',
                    'EXPLORER L’IA ET L’APPRENTISSAGE FÉDÉRÉ (*)',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'BAEMQ' => [
                    'MICROBIOLOGIE ET SÉCURITÉ DES ALIMENTS (*)',
                    'QUALITÉ SANITAIRE DES ALIMENTS',
                    'BIOLOGIE MOLÉCULAIRE ET APPROCHES GÉNOMIQUES',
                    'MANAGEMENT DE LA QUALITÉ EN AGROALIMENTAIRE',
                    'DESIGN THINKING ET CULTURE ENTREPRENEURIALE',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'BEVP' => [
                    'BIOCHIMIE ET PHYSIOLOGIE DE LA NUTRITION MINÉRALE',
                    'BIODIVERSITÉ ET AMÉLIORATION DES PLANTES HORTICOLES',
                    'INGÉNIERIE DE LA CULTURE DES TISSUS VÉGÉTAUX',
                    'AMÉLIORATION GÉNÉTIQUE DES ARBRES FORESTIERS',
                    'INTERACTIONS PLANTES-MICROORGANISMES PATHOGÈNES (*)',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                    'MÉTHODOLOGIE DE LA RECHERCHE EN SCIENCES',
                ],
                'BIAM' => [
                    'Data Mining// Graph mining',
                    'Méthodologie de Recherche Scientifique *',
                    'Réseaux de Neurones Artificiels / Profonds',
                    'Imagerie Biomédicale/Vidéos Biomédicales',
                    'Bioinformatics I *',
                    'Génomique // Fondamentaux des Pathologies Cardiovasculaires',
                    'Culture entrepreneuriale et Techniques de Communication',
                ],
                'BIOMSSI' => [
                    'Entomologie médicale et maladies zoonotiques',
                    'Physiopathologie',
                    'Physique médicale appliquée',
                    'Management de la qualité dans le secteur biomédical',
                    'Biotechnologie plantes aromatiques et médicinales',
                    'Culture entrepreneuriale et techniques de communication',
                    'Méthodologie de la recherche en sciences et technologies',
                ],
                'CAE' => [
                    'GENIE DES PROCEDES ET ENVIRONNEMENT',
                    'GESTION DES DECHETS SOLIDES / CHANGENEMENT CLIMATIQUE',
                    'CORROSION ET ANTICORROSION',
                    'CHIMIE ANALYTIQUE APPROFONDIE (*)',
                    'EFFLUENTS LIQUIDE',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'M2A' => [
                    'COLLOIDAL AND POLYMERIC SYSTEMS: DRUG DELIVERY (*)',
                    'DENSITY FUNCTIONAL THEORY AND MOLECULAR DYNAMICS (*)',
                    'MATÉRIAUX POUR LE STOCKAGE ÉLECTRIQUE ET BATTERIES',
                    'CONVERSION PHOTOVOLTAÏQUE ET PROPRIÉTÉS PHOTOPILES',
                    'TECHNOLOGIE DES CAPTEURS',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'M2SD' => [
                    'STATISTIQUE MATHÉMATIQUE',
                    'PROCESSUS STOCHASTIQUE À TEMPS DISCRET',
                    'CALCUL STOCHASTIQUE',
                    'ANALYSE DES DONNÉES AVANCÉ',
                    'BAYESIAN METHODS (*)',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'MAER' => [
                    'ELECTROTECHNIQUE',
                    'SYSTÈME DE CONVERSION DE L’ÉNERGIE THERMIQUE',
                    'CAPTEURS PHYSIQUES ET LOGICIELS',
                    'INTELLIGENT CONTROL OF NONLINEAR SYSTEMS (*)',
                    'CONCEPTION ET PROGRAMMATION DES SYSTÈMES EMBARQUÉS',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'MASI' => [
                    'THÉORIE DES POINTS CRITIQUES',
                    'MÉTHODE DES VOLUMES FINIS POUR LES EDPs',
                    'MÉTHODES NUMÉRIQUES POUR LE CALCUL SCIENTIFIQUE',
                    'INTELLIGENCE ARTIFICIELLE',
                    'MODÉLISATION MATHÉMATIQUE ET SYSTÈMES DYNAMIQUES (*)',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TEC',
                ],
                'MMP' => [
                    'THÉORIE DES VALUATIONS ET ANALYSE P-ADIQUE',
                    'ALGÈBRE HOMOLOGIQUE (*)',
                    'ALGÈBRES DE BANACH',
                    'THÉORIE DES OPÉRATEURS',
                    'THÉORIE DES CATÉGORIES',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
                'PNOMER' => [
                    'PHYSIQUE DES MATÉRIAUX I',
                    'INFORMATIQUE',
                    'PHYSIQUE DES SEMICONDUCTEURS',
                    'POLYMÈRES SEMICONDUCTEURS NOUVEAUX MATÉRIAUX',
                    'METHODS OF MATERIALS CHARACTERIZATION',
                    'MÉTHODOLOGIE DE LA RECHERCHE SCIENTIFIQUE',
                    'CULTURE ENTREPRENEURIALE ET TIC',
                ],
            ],

            /* ==========================================================
               SEMESTRE 3 (Master)
               ========================================================== */
            'S3' => [
                '2ESI' => [
                    'SYSTÈMES EMBARQUÉS TEMPS RÉEL',
                    'AUTOMATIQUE TEMPS RÉEL',
                    'CONCEPTION BASÉE SUR L’OUTIL EDA',
                    'PROGRAMMATION DES SYSTÈMES EMBARQUÉS',
                    'APPRENTISSAGE AUTOMATIQUE ET APPROFONDI (*)',
                    'ROBOTIQUE ET INTELLIGENCE ARTIFICIELLE EMBARQUÉE',
                    'SÉCURITÉ DES SYSTÈMES EMBARQUÉS ET MOBILES',
                ],
                'WISD' => [
                    'LLM ET WEB MINING (*)',
                    'BUSINESS INTELLIGENCE',
                    'DATA WAREHOUSE',
                    'MACHINE LEARNING ET DEEP LEARNING',
                    'DATA MINING',
                    'EXPLORATION INTELLIGENTE DES IMAGES',
                    'ETHIQUE DES ALGORITHMES',
                ],
                'MLAIM' => [
                    "FOUILLE D'IMAGES",
                    'APPRENTISSAGE FÉDÉRÉ / THÉORIE DES GRAPHES',
                    'RÉALITÉ AUGMENTÉE / VÉHICULE INTELLIGENT',
                    'DEEP REINFORCEMENT LEARNING AND MULTIMODAL GENERATIVE AI',
                    'AUTOMATIC SPEECH RECOGNITION / TEXTUAL CORPUS',
                    'ANALYSE DES IMAGES AVANCÉE / MÉDICALES',
                    'VIDEO ANALYSIS',
                ],
                'BDSI' => [
                    'APPRENTISSAGE PROFOND ET PAR RENFORCEMENT',
                    'TRAITEMENT DU LANGAGE NATUREL ET FOUILLE DE TEXTE',
                    'BLOCKCHAIN ET APPLICATIONS DÉCENTRALISÉES',
                    'FOUILLE DU WEB ET REPRÉSENTATION DES LANGUES (*)',
                    'GEOIA',
                    'BIG DATA ANALYTICS II / AGENTS INTELLIGENTS (*)',
                    'BUSINESS INTELLIGENCE ET APPLICATIONS DE L’IA',
                ],
                'BAEMQ' => [
                    'TRAITEMENTS TECHNOLOGIQUES DES DENRÉES ALIMENTAIRES (*)',
                    'BIOSÉCURITÉ ET BIOSÛRETÉ DANS LES LABORATOIRES',
                    'CONCEPTION ET FORMULATION DES ALIMENTS',
                    'MÉTROLOGIE EN AGROALIMENTAIRE',
                    'ENVIRONNEMENT DE L’ENTREPRISE ET GESTION DE PROJETS',
                    'NUTRITION HUMAINE',
                    'INTELLIGENCE ARTIFICIELLE EN BIOTECHNOLOGIE ALIMENTAIRE',
                ],
                'BEVP' => [
                    'GÉNOMIQUE, ÉPIGÉNÉTIQUE ET BIOINFORMATIQUE',
                    "ÉTUDE D'IMPACT SUR L'ENVIRONNEMENT / CONCEPT QUALITÉ",
                    'BIOTECHNOLOGIE MICROBIENNE',
                    "BIOTECHNOLOGIE DE L'ÉPURATION",
                    'VALORISATION DES PLANTES AROMATIQUES ET MÉDICINALES',
                    'ENTOMOLOGIE : IDENTIFICATION DES NUISIBLES',
                    'BIOTECHNOLOGIE DES ALGUES ET APPLICATION INDUSTRIELLE (*)',
                ],
                'BIAM' => [
                    'Advanced AI Models & Architectures/ AI Powered Security',
                    'Big Data II',
                    'Cloud Computing & CyberBioSecurity',
                    'Explainable Artificial Intelligence (XAI)',
                    'Bioinformatics II *',
                    'Advanced Bioinformatics for Next Generation Sequencing *',
                    'Multiomics // Multimodal Integrative data Analysis',
                ],
                'BIOMSSI' => [
                    'Parasitologie médicale',
                    'Biosécurité et sécurité chimique dans les laboratoires',
                    'Biochimie instrumentale et perturbations biochimiques',
                    'Bactériologie médicale et antibiorésistance',
                    'Epidémiologie, Ethique et humanités médicales',
                    'Intelligence artificielle pour l’analyse bioinformatique',
                    'Initiation au développement des médicaments',
                ],
                'CAE' => [
                    'CHIMIE ANALYTIQUE APLIQUEE ET ANALYSE INDUSTRIELLE',
                    'CHIMIE QUANTIQUE AVANCEE',
                    "EDUCATION, INFORMATION ET ETUDE DE L'IMPACT ENVIRONNEMENTALE",
                    "CHIMIOMETRIE / METHODOLOGIE DES PLANS D'EXPERIENCES",
                    'THECHNIQUES DES METHODES de SEPARATION AVANCEE',
                    'ANALYSES ENVIRONNEMENTALES (*)',
                    "ENERGIE PROPRES ET STOCKAGE D'ENERGIE",
                ],
                'M2A' => [
                    'HYDROGÈNE VERT: PRODUCTION, STOCKAGE ET CONVERSION',
                    'MATÉRIAUX PÉROVSKITES ET APPLICATIONS PHOTOVOLTAÏQUES',
                    'THÉORIE PHYSIQUE DES LIQUIDES',
                    'ADVANCED PROGRAMMING AND MACHINE LEARNING FOR MATERIALS (*)',
                    'TECHNIQUES DE CARACTÉRISATION DES MATÉRIAUX',
                    'MATÉRIAUX POUR LES APPLICATIONS BIOMÉDICALES',
                    'PROCÉDÉS DE FABRICATION DES MATÉRIAUX INDUSTRIELS',
                ],
                'M2SD' => [
                    'MODÉLISATION STATISTIQUE ET SIMULATION',
                    'PROCESSUS DE LÉVY ET MODÈLES ÉPIDÉMIQUES STOCHASTIQUES',
                    'SÉRIES CHRONOLOGIQUES',
                    'THÉORIE DES ENSEMBLES FLOUS ET APPLICATIONS',
                    'ACTUARIAT',
                    'LOGICIELS MATHÉMATIQUES',
                    'STOCHASTIC DIFFERENTIAL EQUATIONS IN FINANCE (*)',
                ],
                'MAER' => [
                    'COMMANDE ADAPTATIVE',
                    'SYSTÈME DE CONVERSION DE L’ÉNERGIE PHOTOVOLTAÏQUE',
                    'SYSTÈME DE CONVERSION DE L’ÉNERGIE ÉOLIENNE',
                    'SMART GRID AND MEASUREMENT SYSTEMS (*)',
                    'RÉSEAUX ÉLECTRIQUES ET TRANSPORT D’ÉNERGIE',
                    'STOCKAGE D’ÉNERGIE ET TECHNIQUES D’INTÉGRATION',
                    'ENERGIE VERTE ET ENVIRONNEMENT',
                ],
                'MASI' => [
                    'RÉSOLUTION DES EDPs PAR ÉLÉMENTS FINIS',
                    'PROBLÈMES D’ÉVOLUTION NON-LINÉAIRES',
                    'EDP ELLIPTIQUES NON LINÉAIRES',
                    'MATHÉMATIQUES APPLIQUÉES À LA BIOLOGIE',
                    'ÉQUATIONS DIFFÉRENTIELLES STOCHASTIQUES ET APPLICATIONS',
                    'CONTRÔLE OPTIMAL',
                    'RÉSEAUX NEURONAUX PROFONDS POUR LA MODÉLISATION (*)',
                ],
                'MMP' => [
                    'THÉORIE DES SEMI-GROUPES (*)',
                    'OPTIMISATION',
                    'GÉOMÉTRIE RIEMANNIENNE',
                    'CRYPTOGRAPHIE',
                    'ALGÈBRES CENTRALES SIMPLES',
                    'THÉORIE ALGÉBRIQUE DES NOMBRES',
                    'ANALYSE FONCTIONNELLE NON ARCHIMÉDIENNE',
                ],
                'PNOMER' => [
                    'PHYSIQUE DES COMPOSANTS MICROOPTOÉLECTRONIQUES',
                    'MATÉRIAUX POUR L’ENERGIE SOLAIRE',
                    'TECHNOLOGIE DE BATTERIE POUR LES ENERGIES RENOUVELABLES',
                    'TRANSFERT THERMIQUE ET MODÉLISATION',
                    'NANOMATÉRIAUX ORGANIQUE NANOCOMPOSITES',
                    'PHYSIQUE DES MATÉRIAUX II',
                    'NANOMATERIALS FOR ENERGY: PROPERTIES AND APPLICATIONS',
                ],
            ],

            /* ==========================================================
               SEMESTRE 4 (Master - PFE)
               ========================================================== */
            'S4' => [
                '2ESI' => ['PROJET DE FIN D’ÉTUDES'],
                'WISD' => ['PROJET DE FIN D’ÉTUDES'],
                'MLAIM' => ['PROJET DE FIN D’ÉTUDES'],
                'BDSI' => ['PROJET DE FIN D’ÉTUDES'],
                'BAEMQ' => ['PROJET DE FIN D’ÉTUDES'],
                'BEVP' => ['PROJET DE FIN D’ÉTUDES'],
                'BIAM' => ['PROJET DE FIN D’ÉTUDES'],
                'BIOMSSI' => ['PROJET DE FIN D’ÉTUDES'],
                'CAE' => ['PROJET DE FIN D’ÉTUDES'],
                'M2A' => ['PROJET DE FIN D’ÉTUDES'],
                'M2SD' => ['PROJET DE FIN D’ÉTUDES'],
                'MAER' => ['PROJET DE FIN D’ÉTUDES'],
                'MASI' => ['PROJET DE FIN D’ÉTUDES'],
                'MMP' => ['PROJET DE FIN D’ÉTUDES'],
                'PNOMER' => ['PROJET DE FIN D’ÉTUDES'],
            ],
        ];

        foreach ($data as $semestre => $filieres) {
            foreach ($filieres as $key => $modules) {

                // Recherche flexible de la filière (Nom contient "WISD" ET "S1")
                // Exemple : "WISD - S1" sera trouvé si $key="WISD" et $semestre="S1"
                $filiere = Filiere::where('nom', 'LIKE', "%$key%")
                    ->where('nom', 'LIKE', "%$semestre%")
                    ->first();

                if (!$filiere) {
                    // Optionnel : Message si la filière n'existe pas
                    // $this->command->warn("Filière introuvable pour $key - $semestre");
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
