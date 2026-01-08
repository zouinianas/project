<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\CourrierDepart; // Notre nouveau Modèle
use PhpOffice\PhpWord\TemplateProcessor; // Pour générer le Word

class AdminController extends Controller
{
    // =========================================================
    // 1. AUTHENTIFICATION & PROFIL (On garde l'existant)
    // =========================================================

    public function logoutHandler(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
               ->with('fail', 'Vous êtes déconnecté.');
    }

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
        }else{
            return response()->json(['status'=>0, 'msg'=>'Erreur lors du chargement.']);
        }
    }

    // =========================================================
    // 2. TABLEAU DE BORD (Accueil)
    // =========================================================

    public function adminDashboard()
    {
        return view('back.pages.dashboard', ['pageTitle' => 'Tableau de Bord']);
    }

    // =========================================================
    // 3. GESTION DES BORDEREAUX (NOUVEAU)
    // =========================================================

    /**
     * Télécharge le Bordereau en format Word (.docx)
     */
    public function downloadBordereau($id)
    {
        // 1. Récupérer les données
        $courrier = CourrierDepart::findOrFail($id);

        // 2. Chemin du Template (assurez-vous d'avoir créé le dossier storage/app/templates)
        $templatePath = storage_path('app/templates/template_bordereau.docx');

        // Vérification de sécurité
        if (!file_exists($templatePath)) {
            return back()->with('fail', 'Erreur : Le fichier modèle (template_bordereau.docx) est introuvable.');
        }

        // 3. Chargement du processeur Word
        $templateProcessor = new TemplateProcessor($templatePath);

        // 4. Remplissage des variables (Correspondance BDD -> Word)
        $templateProcessor->setValue('date', $courrier->date_depart->format('d/m/Y'));
        $templateProcessor->setValue('destinataire', $courrier->destinataire);
        $templateProcessor->setValue('numero', $courrier->numero_ordre . '/' . $courrier->annee);

        // Gestion des sauts de ligne dans le contenu
        $contenuTraite = str_replace("\n", "<w:br/>", $courrier->objet);
        $templateProcessor->setValue('contenu', $contenuTraite);

        $templateProcessor->setValue('nombre', $courrier->nombre_pieces > 0 ? $courrier->nombre_pieces : '');
        $templateProcessor->setValue('remarques', $courrier->observation ?? '');

        // 5. Sauvegarde temporaire et Téléchargement
        $fileName = 'Bordereau_' . $courrier->numero_ordre . '_' . $courrier->annee . '.docx';
        $savePath = storage_path('app/public/' . $fileName);

        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
}
