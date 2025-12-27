<?php

namespace App\Livewire\Admin;

use App\Models\Personnel;
use App\Models\Departement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionPersonnels extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Propriétés du formulaire (Modal)
    public $nom;
    public $departement_id;
    public $personnel_id;

    // Propriétés de l'état
    public $isUpdateMode = false;

    // FILTRES & RECHERCHE
    public $search = '';
    public $currentDepartementId = null; // null = Afficher tous

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'nom' => [
                'required',
                'string',
                'max:191',
                // Forcer la majuscule pour la cohérence
                function ($attribute, $value, $fail) {
                    if (strtoupper($value) !== $value) {
                       // Optionnel
                    }
                },
                Rule::unique('personnels')->ignore($this->personnel_id)
            ],
            'departement_id' => 'required|exists:departements,id',
        ];
    }

    protected $messages = [
        'nom.unique' => 'Ce nom de personnel existe déjà.',
        'departement_id.required' => 'Veuillez sélectionner un département administratif.',
    ];

    // --- LOGIQUE DE FILTRAGE PAR BOUTONS ---

    public function filterByDepartement($id)
    {
        $this->currentDepartementId = $id; // Si null, ça affiche "Tous"
        $this->resetPage(); // Revenir à la page 1 quand on change de filtre
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // --- MODALS & CRUD ---

    public function openModalCreate()
    {
        $this->isUpdateMode = false;
        $this->resetForm();
        // Si un département est déjà filtré/sélectionné, on pré-remplit le select du modal
        if($this->currentDepartementId) {
            $this->departement_id = $this->currentDepartementId;
        }
        $this->dispatch('showPersonnelModal');
    }

    public function openModalEdit(Personnel $personnel)
    {
        $this->isUpdateMode = true;
        $this->personnel_id = $personnel->id;
        $this->nom = $personnel->nom;
        $this->departement_id = $personnel->departement_id;
        $this->dispatch('showPersonnelModal');
    }

    public function closeModal()
    {
        $this->dispatch('hidePersonnelModal');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nom', 'departement_id', 'personnel_id']);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->nom = strtoupper($this->nom);

        $this->validate();

        Personnel::updateOrCreate(
            ['id' => $this->personnel_id],
            [
                'nom' => $this->nom,
                'departement_id' => $this->departement_id,
            ]
        );

        $message = $this->isUpdateMode ? 'Personnel mis à jour.' : 'Personnel ajouté avec succès.';
        $this->dispatch('showToastr', ['type' => 'success', 'message' => $message]);

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('showDeleteConfirmation', $id);
    }

    public function delete($id)
    {
        Personnel::destroy($id);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Personnel supprimé avec succès.']);
    }

    public function render()
    {
        $departements = Departement::orderBy('nom', 'asc')->get();

        // REQUETE PRINCIPALE
        $query = Personnel::with('departement')
            ->leftJoin('departements', 'personnels.departement_id', '=', 'departements.id')
            ->select('personnels.*');

        // 1. Filtrage par Département (Boutons)
        if ($this->currentDepartementId) {
            $query->where('personnels.departement_id', $this->currentDepartementId);
        }

        // 2. Recherche (Barre de recherche)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('personnels.nom', 'like', '%' . $this->search . '%')
                  ->orWhere('departements.nom', 'like', '%' . $this->search . '%');
            });
        }

        // 3. Tri (Alphabétique par nom de prof)
        // Le user veut "les profs s'affichent en ordre alphabétique"
        $personnels = $query->orderBy('personnels.nom', 'asc')
                            ->paginate(12); // Pagination un peu plus large

        return view('livewire.admin.gestion-personnels', [
            'personnels' => $personnels,
            'departements' => $departements,
        ]);
    }
}
