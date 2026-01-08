<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourrierDepart;
use Illuminate\Support\Facades\Auth;

class GestionBordereaux extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // --- CHAMPS DU FORMULAIRE ---
    public $courrier_id;
    public $date_depart;
    public $destinataire;
    public $objet;
    public $nombre_pieces = 0;
    public $observation;

    // --- ÉTAT DU COMPOSANT ---
    public $search = '';
    public $isUpdateMode = false;

    // Initialisation
    public function mount()
    {
        $this->date_depart = date('Y-m-d'); // Date d'aujourd'hui par défaut
    }

    // Règles de validation
    protected $rules = [
        'date_depart' => 'required|date',
        'destinataire' => 'required|string|max:255',
        'objet' => 'required|string',
        'nombre_pieces' => 'integer|min:0',
    ];

    // --- 1. SAUVEGARDE (Création ou Modification) ---
    public function save()
    {
        $this->validate();

        if ($this->isUpdateMode) {
            // MODE MODIFICATION
            $courrier = CourrierDepart::findOrFail($this->courrier_id);

            // Si l'année de la date change, attention au numéro (ici on garde le numéro original pour simplifier)
            $courrier->update([
                'date_depart' => $this->date_depart,
                'destinataire' => $this->destinataire,
                'objet' => $this->objet,
                'nombre_pieces' => $this->nombre_pieces,
                'observation' => $this->observation,
            ]);

            $msg = 'Mise à jour effectuée avec succès.';

        } else {
            // MODE CRÉATION (NOUVEAU)

            // A. On récupère l'année de la date choisie (ex: 2026)
            $anneeChoisie = date('Y', strtotime($this->date_depart));

            // B. Algorithme d'Auto-Incrémentation
            // On cherche le plus grand numéro existant pour cette année là
            $lastNum = CourrierDepart::where('annee', $anneeChoisie)->max('numero_ordre');

            // Si on trouve (ex: 15), le nouveau est 16. Sinon c'est 1.
            $newNum = $lastNum ? $lastNum + 1 : 1;

            CourrierDepart::create([
                'numero_ordre' => $newNum,
                'annee' => $anneeChoisie,
                'date_depart' => $this->date_depart,
                'destinataire' => $this->destinataire,
                'objet' => $this->objet,
                'nombre_pieces' => $this->nombre_pieces,
                'observation' => $this->observation,
                'user_id' => Auth::id(), // L'utilisateur connecté
            ]);

            $msg = "Bordereau N° $newNum/$anneeChoisie créé avec succès.";
        }

        // Fermer le modal et afficher le message
        $this->dispatch('hideBordereauModal');
        $this->dispatch('showToastr', ['type' => 'success', 'message' => $msg]);
        $this->resetForm();
    }

    // --- 2. ÉDITION (Charger les données dans le modal) ---
    public function edit($id)
    {
        $c = CourrierDepart::findOrFail($id);

        $this->courrier_id = $c->id;
        $this->date_depart = $c->date_depart->format('Y-m-d');
        $this->destinataire = $c->destinataire;
        $this->objet = $c->objet;
        $this->nombre_pieces = $c->nombre_pieces;
        $this->observation = $c->observation;

        $this->isUpdateMode = true;
        $this->dispatch('showBordereauModal');
    }

    // --- 3. SUPPRESSION ---
    public function delete($id)
    {
        CourrierDepart::destroy($id);
        $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Bordereau supprimé.']);
    }

    // --- 4. OUTILS ---
    public function openModal()
    {
        $this->resetForm();
        $this->isUpdateMode = false;
        $this->dispatch('showBordereauModal');
    }

    public function resetForm()
    {
        $this->reset(['destinataire', 'objet', 'nombre_pieces', 'observation', 'courrier_id', 'isUpdateMode']);
        $this->date_depart = date('Y-m-d');
        $this->resetValidation();
    }

    // --- 5. AFFICHAGE (Render) ---
    public function render()
    {
        // Recherche et Tri (Année Décroissante, puis Numéro Décroissant)
        $courriers = CourrierDepart::query()
            ->where(function($q) {
                $q->where('destinataire', 'like', '%'.$this->search.'%')
                  ->orWhere('objet', 'like', '%'.$this->search.'%')
                  ->orWhere('numero_ordre', 'like', '%'.$this->search.'%');
            })
            ->orderBy('annee', 'desc')
            ->orderBy('numero_ordre', 'desc')
            ->paginate(10);

        return view('livewire.admin.gestion-bordereaux', [
            'courriers' => $courriers
        ]);
    }
}
