<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Sortie;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{

    public function logoutHandler(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
               ->with('fail', 'You are now logged out!');
    }

    /* =========================================================
     |  PAGES D’ADMINISTRATION
     ========================================================= */
    public function adminDashboard() {
        return view('back.pages.dashboard', ['pageTitle' => 'Dashboard']);
    }

    public function departementsPage() {
        return view('back.pages.departements_page', ['pageTitle' => 'Gérer les Départements']);
    }

    public function filieresPage() {
        return view('back.pages.filieres_page', ['pageTitle' => 'Gérer les Filières']);
    }

    public function destinationsPage() {
        return view('back.pages.destinations_page', ['pageTitle' => 'Gérer les Destinations']);
    }

    public function modulesPage() {
        return view('back.pages.modules_page', ['pageTitle' => 'Gérer les Modules']);
    }

    public function personnelsPage() {
        return view('back.pages.personnels_page', ['pageTitle' => 'Gérer le Personnel']);
    }

    /* =========================================================
     |  IMPRESSION ORDRES INDIVIDUELS
     ========================================================= */
    public function printOrdreMission(Request $request, $id) {
        return $this->handleSinglePrint($request, $id);
    }

    public function printOrdreLibre(Request $request, $id) {
        return $this->handleSinglePrint($request, $id);
    }

    private function handleSinglePrint(Request $request, $id)
    {
        $format = $request->query('format', 'word');
        $docxPath = $this->generateSingleDocx($id);

        if ($format === 'pdf') {
            $pdfPath = $this->convertWordToPdf($docxPath);
            return response()->download($pdfPath)->deleteFileAfterSend(true);
        }
        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    private function generateSingleDocx($id): string
    {
        $sortie = Sortie::with(['module.filiere.departement', 'destination', 'encadrants', 'departement'])->find($id);
        if (!$sortie) abort(404);

        $templatePath = storage_path('app/template.docx');
        if (!file_exists($templatePath)) abort(500, 'template.docx introuvable');

        $processor = new TemplateProcessor($templatePath);
        $this->fillTemplateData($processor, $sortie);

        $prefix = $sortie->module_id ? 'Ordre_Mission_' : 'Ordre_Libre_';
        $fileName = $prefix . $sortie->id . '.docx';
        $tempDir = storage_path('app/temp');
        $this->ensureDirectoryExists($tempDir);
        $path = $tempDir . '/' . $fileName;
        $processor->saveAs($path);

        return $path;
    }

    /* =========================================================
     |  BILAN SAISONNIER (WORD, PDF, EXCEL)
     |  Logique : Saison = 01 Sept (Année) -> 30 Juin (Année + 1)
     ========================================================= */
    public function downloadYearArchive(Request $request, $year)
    {
        $format = $request->query('format', 'word');

        // Définition de la Saison Universitaire
        $startDate = Carbon::create($year, 9, 1)->startOfDay();       // 01 Septembre
        $endDate   = Carbon::create($year + 1, 6, 30)->endOfDay();    // 30 Juin

        // 1. Export EXCEL (Version filtrée + Calendrier complet)
        if ($format === 'excel') {
            return $this->generateExcelExport($startDate, $endDate, $year);
        }

        // 2. Export WORD / PDF (Version COMPLETE : Tout inclus)
        $docxPath = $this->generateSeasonDocx($startDate, $endDate, $year);

        if ($format === 'pdf') {
            $pdfPath = $this->convertWordToPdf($docxPath);
            return response()->download($pdfPath)->deleteFileAfterSend(true);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    // --- GÉNÉRATEUR EXCEL (FILTRÉ : Pas d'ordres libres) ---
    private function generateExcelExport($startDate, $endDate, $year)
    {
        // ICI : On FILTRE pour ne garder que les sorties avec Module, Destination ou Réservées.
        // Les "Ordres Libres" purs sont exclus de l'Excel.
        $sorties = Sortie::with(['module.filiere.departement', 'destination', 'encadrants', 'departement'])
            ->whereBetween('date_debut', [$startDate, $endDate])
            ->where(function($q) {
                $q->whereNotNull('module_id')       // Sortie standard
                  ->orWhereNotNull('destination_id') // Sortie spéciale / médecine
                  ->orWhere('statut', 'Réservé');    // Réservation
            })
            ->orderBy('date_debut', 'asc')
            ->get();

        // Groupement par date
        $sortiesParDate = $sorties->groupBy(function($item) {
            return $item->date_debut->format('Y-m-d');
        });

        $filename = "Bilan_Saison_" . $year . "-" . ($year + 1) . ".xls";

        // Construction du HTML pour Excel
        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif;">';

        // En-tête (Pas de colonne Chauffeur)
        $html .= '<tr style="background-color: #4e73df; color: white; font-weight: bold; text-align: center;">';
        $html .= '<th style="padding: 10px;">Date</th>';
        $html .= '<th style="padding: 10px;">Département</th>';
        $html .= '<th style="padding: 10px;">Semestre/Niveau</th>';
        $html .= '<th style="padding: 10px;">Module / Objet</th>';
        $html .= '<th style="padding: 10px;">Lieu (Destination)</th>';
        $html .= '<th style="padding: 10px;">Transport</th>';
        $html .= '<th style="padding: 10px;">Accompagnants / Personnel</th>';
        $html .= '</tr>';

        // Boucle sur TOUS les jours (01 Sept -> 30 Juin)
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $dateObj) {
            $dateKey = $dateObj->format('Y-m-d');
            $dateAffiche = $dateObj->format('d/m/Y');

            if ($sortiesParDate->has($dateKey)) {
                // S'il y a des sorties ce jour-là
                foreach ($sortiesParDate[$dateKey] as $sortie) {

                    // Couleur & Nom Département
                    $bgRow = '#ffffff';
                    $nomDept = '';

                    if ($sortie->departement) {
                        $nomDept = $sortie->departement->nom;
                        $bgRow = $sortie->departement->couleur ?? '#ffffff';
                    } elseif ($sortie->module && $sortie->module->filiere && $sortie->module->filiere->departement) {
                        $nomDept = $sortie->module->filiere->departement->nom;
                        $bgRow = $sortie->module->filiere->departement->couleur ?? '#ffffff';
                    } elseif ($sortie->objet && stripos($sortie->objet, 'Médecine') !== false) {
                         $nomDept = 'Médecine';
                         $bgRow = '#E74C3C';
                    }

                    // Données
                    if ($sortie->module && $sortie->module->filiere) {
                        $semestre = ($sortie->module->filiere->niveau->value ?? '') . ' - ' . $sortie->module->filiere->nom;
                    } else {
                        $semestre = 'Hors-Cursus';
                    }

                    $module = $sortie->module ? $sortie->module->nom : ($sortie->objet ?? '');
                    $lieu = $sortie->destination ? $sortie->destination->nom : '';

                    // Nettoyage Transport
                    $rawTransport = $sortie->transport ? $sortie->transport->value : '';
                    $transport = '';
                    if (stripos($rawTransport, 'MINI BUS') !== false) {
                        $transport = 'Mini Bus';
                    } elseif (stripos($rawTransport, 'BUS') !== false) {
                        $transport = 'Bus';
                    } else {
                        $transport = $rawTransport;
                    }

                    // Accompagnants
                    $profs = [];
                    foreach($sortie->encadrants as $p) $profs[] = $p->nom;
                    if(empty($profs) && $sortie->personnel) $profs[] = $sortie->personnel;
                    $accompagnants = implode(' / ', $profs);

                    // Ligne avec couleur de fond
                    $html .= "<tr style='background-color: $bgRow;'>";
                    $html .= "<td style='text-align: center;'>$dateAffiche</td>";
                    $html .= "<td style='text-align: center; font-weight:bold;'>$nomDept</td>";
                    $html .= "<td>$semestre</td>";
                    $html .= "<td>$module</td>";
                    $html .= "<td>$lieu</td>";
                    $html .= "<td>$transport</td>";
                    $html .= "<td>$accompagnants</td>";
                    $html .= '</tr>';
                }
            } else {
                // Jour vide (Calendrier complet)
                $html .= "<tr style='background-color: #ffffff;'>";
                $html .= "<td style='text-align: center;'>$dateAffiche</td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "</tr>";
            }
        }

        $html .= '</table>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    // --- GÉNÉRATEUR WORD / PDF (COMPLET : Tout est inclus) ---
    private function generateSeasonDocx($startDate, $endDate, $year): string
    {
        set_time_limit(600); ini_set('memory_limit', '512M');

        // ICI : AUCUN FILTRE (Pas de whereNotNull...). On prend TOUTES les sorties, y compris les libres.
        $sorties = Sortie::with(['module.filiere.departement', 'destination', 'encadrants'])
            ->whereBetween('date_debut', [$startDate, $endDate])
            ->orderBy('numero_ordre')
            ->get();

        if ($sorties->isEmpty()) abort(404, 'Aucune sortie trouvée pour cette saison.');

        $templatePath = storage_path('app/template_bilan.docx');
        if (!file_exists($templatePath)) abort(500, 'template_bilan.docx introuvable');

        $processor = new TemplateProcessor($templatePath);

        $processor->setValue('saison', $year . '/' . ($year + 1));
        $processor->cloneBlock('mission_block', $sorties->count(), true, true);

        $i = 1;
        foreach ($sorties as $sortie) {
            $this->fillTemplateData($processor, $sortie, $i);
            $i++;
        }

        $fileName = 'Bilan_Saison_' . $year . '-' . ($year + 1) . '.docx';
        $tempDir = storage_path('app/temp');
        $this->ensureDirectoryExists($tempDir);
        $path = $tempDir . '/' . $fileName;
        $processor->saveAs($path);
        return $path;
    }

    private function convertWordToPdf(string $docxPath): string
    {
        $outDir = dirname($docxPath);
        $command = '"C:\Program Files\LibreOffice\program\soffice.exe" --headless --convert-to pdf --outdir ' . escapeshellarg($outDir) . ' ' . escapeshellarg($docxPath);
        exec($command);
        return str_replace('.docx', '.pdf', $docxPath);
    }

    private function prepareDataForView($sortie)
    {
        $dateDebut = Carbon::parse($sortie->date_debut);
        $dateFin   = $sortie->date_fin ? Carbon::parse($sortie->date_fin) : $dateDebut;
        $destination = $sortie->destination ? $sortie->destination->nom : 'Voir objet';

        if ($sortie->module_id) {
            $module = $sortie->module->nom ?? '';
            $filiere = $sortie->module->filiere->nom ?? '';
            $niveau = $sortie->module->filiere->niveau->value ?? '';
            $objet = "$module – Etudiants $filiere ($niveau)";
            $enseignants = implode(', ', $sortie->encadrants->pluck('nom')->toArray());
        } else {
            $objet = $sortie->objet ?? '';
            $enseignants = $sortie->personnel ?? '';
        }

        $dateCreation = $sortie->date_validation ? 'Fès le ' . Carbon::parse($sortie->date_validation)->format('d/m/Y') : 'Fès le ' . now()->format('d/m/Y');

        return [
            'numero_ordre' => $sortie->numero_ordre . '/' . $dateDebut->format('y'),
            'chauffeur' => $sortie->chauffeur ?? '',
            'destination' => $destination,
            'objet_mission' => $objet,
            'transport' => $sortie->transport?->value ?? '',
            'enseignants' => $enseignants,
            'date_debut' => $dateDebut->format('d/m/Y'),
            'date_fin' => $dateFin->format('d/m/Y'),
            'date_creation' => $dateCreation,
        ];
    }

    private function fillTemplateData($processor, $sortie, $index = null)
    {
        foreach ($this->prepareDataForView($sortie) as $key => $value) {
            $processor->setValue($index ? $key . '#' . $index : $key, $value);
        }
    }

    private function ensureDirectoryExists($path)
    {
        if (!File::isDirectory($path)) File::makeDirectory($path, 0755, true);
    }
}
