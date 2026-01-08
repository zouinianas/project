<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\CourrierDepart; // Import du modèle
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class AdminController extends Controller
{
    // --- GESTION AUTH ---
    public function logoutHandler(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'Vous êtes déconnecté.');
    }

    // --- DASHBOARD (MODIFIÉ) ---
    public function adminDashboard()
    {
        // 1. Statistiques
        $stats = [
            'total_annee' => CourrierDepart::where('annee', date('Y'))->count(),
            'total_aujourdhui' => CourrierDepart::whereDate('date_depart', Carbon::today())->count(),
            'total_global' => CourrierDepart::count(),
        ];

        // 2. Les 5 derniers bordereaux (Pour affichage rapide)
        $recents = CourrierDepart::orderBy('created_at', 'desc')->take(5)->get();

        return view('back.pages.dashboard', [
            'pageTitle' => 'Tableau de bord',
            'stats' => $stats,
            'recents' => $recents
        ]);
    }

    // --- PROFIL ---
    public function profileView()
    {
        return view('back.pages.profile', ['pageTitle' => 'Mon Profil']);
    }

    public function changeProfilePicture(Request $request)
    {
        $user = User::find(Auth::id());
        $path = 'images/users/';
        $file = $request->file('file');
        $old_picture = $user->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $new_picture_name = 'UIMG'.$user->id.time().rand(1,100000).'.jpg';

        if($old_picture != null && File::exists(public_path($file_path))){
            File::delete(public_path($file_path));
        }
        $upload = $file->move(public_path($path), $new_picture_name);
        if($upload){
            $user->update(['picture'=>$new_picture_name]);
            return response()->json(['status'=>1, 'msg'=>'Photo de profil mise à jour.']);
        } else {
            return response()->json(['status'=>0, 'msg'=>'Erreur lors du chargement.']);
        }
    }

    // --- TÉLÉCHARGEMENT WORD ---
    public function downloadBordereau($id)
    {
        $courrier = CourrierDepart::findOrFail($id);
        $templatePath = storage_path('app/templates/template.docx');

        if (!file_exists($templatePath)) {
            // Fallback dossier public si non trouvé dans storage
            $templatePath = public_path('template.docx');
            if(!file_exists($templatePath)){
                 return back()->with('fail', "Le fichier 'template.docx' est introuvable.");
            }
        }

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // Formatage de la date
            $dateFormatted = $courrier->date_depart ? $courrier->date_depart->format('d/m/Y') : '--/--/----';

            // Remplissage des variables
            $templateProcessor->setValue('date', $dateFormatted);
            $templateProcessor->setValue('destinataire', $courrier->destinataire);
            $templateProcessor->setValue('numero', $courrier->numero_ordre . '/' . $courrier->annee);

            // Gestion des sauts de ligne pour l'Objet
            $objetTraite = str_replace("\n", "<w:br/>", $courrier->objet);
            $templateProcessor->setValue('contenu', $objetTraite);

            $templateProcessor->setValue('nombre', $courrier->nombre_pieces);
            $templateProcessor->setValue('remarques', $courrier->observation ?? '');

            // Sauvegarde
            $fileName = 'Bordereau_' . $courrier->numero_ordre . '_' . $courrier->annee . '.docx';
            $tempPath = storage_path('app/public/' . $fileName);

            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0777, true);
            }

            $templateProcessor->saveAs($tempPath);

            return response()->download($tempPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('fail', "Erreur : " . $e->getMessage());
        }
    }
}
