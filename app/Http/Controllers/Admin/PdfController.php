<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourrierDepart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    public function generateBordereau($id)
    {
        try {
            // 1. Récupérer le courrier
            $courrier = CourrierDepart::findOrFail($id);

            // 2. Configuration pour supporter les images locales et l'UTF-8
            $config = [
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ];

            // 3. Chargement de la vue (CORRECTION DU NOM DU FICHIER ICI)
            // On charge 'bordereau_template' car c'est ton fichier avec le design
            $pdf = Pdf::loadView('back.pdf.bordereau_template', [
                'courrier' => $courrier
            ]);

            // 4. Configuration papier
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions($config);

            // 5. Nom du fichier
            $fileName = 'Bordereau_' . $courrier->numero_ordre . '_' . $courrier->annee . '.pdf';

            // 6. Affichage
            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            // En cas d'erreur, on l'affiche pour comprendre (au lieu de la page blanche)
            return response()->json([
                'error' => 'Erreur de génération PDF',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
