<?php

namespace App\Livewire\Admin;

use App\Models\Filiere;
use App\Models\Departement;
use App\Enums\NiveauCycle;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionFilieres extends Component
{
    use WithPagination;

    // --- NAVIGATION ---
    public $selectedDepartementId = null;
    public $selectedNiveau = null;
    public $selectedFiliereGroup = null; // ex: "SMI", "MQL"
    public $selectedSemestre = null;     // ex: "S1"

    // --- FORMULAIRE ---
    public $nom;
    public $departement_id;
    public $niveau;
    public $filiere_id;

    // Propriétés de l'état
    public $isUpdateMode = false;

    // --- NAVIGATION LOGIQUE (Identique aux Modules) ---

    public function selectDepartement($id)
    {
        $this->resetNavigation();
        $this->selectedDepartementId = $id;
        // Par défaut, on se met sur DEUG 1ère année ou le premier cas
        $this->selectedNiveau = NiveauCycle::DEUG_A1->value;
        $this->resetPage();
    }

    public function selectNiveau($niveauValue)
    {
        $this->selectedNiveau = $niveauValue;
        $this->selectedFiliereGroup = null;
        $this->selectedSemestre = null;
        $this->resetPage();
    }

    public function selectFiliereGroup($groupName)
    {
        $this->selectedFiliereGroup = $groupName;
        // Selectionner intelligemment le 1er semestre dispo pour ce cycle
        $enumCase = NiveauCycle::tryFrom($this->selectedNiveau);
        if ($enumCase) {
            $semestres = $enumCase->semestres();
            $this->selectedSemestre = $semestres[0]->value ?? 'S1';
        }
        $this->resetPage();
    }

    public function selectSemestre($semestre)
    {
        $this->selectedSemestre = $semestre;
        $this->resetPage();
    }

    public function resetNavigation()
    {
        $this->selectedDepartementId = null;
        $this->selectedNiveau = null;
        $this->selectedFiliereGroup = null;
        $this->selectedSemestre = null;
        $this->resetPage();
    }

    // --- COMPUTED PROPERTIES ---

    /**
     * Calcule les "Groupes" (Spécialités) disponibles en analysant les noms des filières existantes.
     * Ex: Si on a "SMI - S3", "SMI - S4", le groupe est "SMI".
     */
    public function getAvailableGroupsProperty()
    {
        if (!$this->selectedDepartementId || !$this->selectedNiveau) {
            return [];
        }

        $filieres = Filiere::where('departement_id', $this->selectedDepartementId)
                           ->where('niveau', $this->selectedNiveau)
                           ->get();

        $groups = [];

        foreach ($filieres as $f) {
            // Regex pour extraire le nom de base avant le semestre (ex: "MQL - S1" => "MQL")
            $baseName = preg_replace('/ - S[0-9]+.*$/', '', $f->nom);
            $baseName = trim($baseName);

            if (!in_array($baseName, $groups) && !empty($baseName)) {
                $groups[] = $baseName;
            }
        }

        sort($groups);
        return $groups;
    }


    // --- CRUD ---

    protected function rules()
    {
        return [
            'nom' => [
                'required', 'string', 'max:191',
                Rule::unique('filieres')->ignore($this->filiere_id)->where(function ($query) {
                    return $query->where('departement_id', $this->departement_id)
                                 ->where('niveau', $this->niveau);
                })
            ],
            'departement_id' => 'required|exists:departements,id',
            'niveau' => ['required', Rule::in(array_column(NiveauCycle::cases(), 'value'))],
        ];
    }

    public function openModalCreate()
    {
        $this->isUpdateMode = false;
        $this->resetForm();

        // Pré-remplissage intelligent
        if($this->selectedDepartementId) {
            $this->departement_id = $this->selectedDepartementId;
        }
        if($this->selectedNiveau) {
            $this->niveau = $this->selectedNiveau;
        }

        $this->dispatch('showFiliereModal');
    }

    public function openModalEdit(Filiere $filiere)
    {
        $this->isUpdateMode = true;
        $this->filiere_id = $filiere->id;
        $this->nom = $filiere->nom;
        $this->departement_id = $filiere->departement_id;
        $this->niveau = $filiere->niveau ? $filiere->niveau->value : null;
        $this->dispatch('showFiliereModal');
    }

    public function closeModal()
    {
        $this->dispatch('hideFiliereModal');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nom', 'departement_id', 'niveau', 'filiere_id']);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        Filiere::updateOrCreate(
            ['id' => $this->filiere_id],
            [
                'nom' => $this->nom,
                'departement_id' => $this->departement_id,
                'niveau' => $this->niveau,
            ]
        );

        $message = $this->isUpdateMode ? 'Filière mise à jour.' : 'Filière créée.';
        $this->dispatch('showToastr', ['type' => 'success', 'message' => $message]);
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('showDeleteConfirmation', $id);
    }

    public function delete($id)
    {
        Filiere::destroy($id);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Filière supprimée.']);
    }

    public function render()
    {
        $departements = Departement::orderBy('nom', 'asc')->get();
        $filieres = [];
        $semestresDisponibles = [];

        // Gestion des semestres pour l'affichage des boutons
        if($this->selectedNiveau) {
            $currentNiveauEnum = NiveauCycle::tryFrom($this->selectedNiveau);
            if($currentNiveauEnum) {
                $semestresDisponibles = $currentNiveauEnum->semestres();
            }
        }

        // --- REQUÊTE PRINCIPALE ---
        // On n'affiche le tableau que si tout est sélectionné (pour imiter le module)
        // OU on peut être plus souple. Ici je garde la logique stricte "Entonnoir".
        if ($this->selectedDepartementId && $this->selectedFiliereGroup && $this->selectedSemestre) {

            $filieres = Filiere::where('departement_id', $this->selectedDepartementId)
                ->where('niveau', $this->selectedNiveau)
                // Filtre par Semestre
                ->where(function($q) {
                    // On suppose que le semestre est soit dans une colonne 'semestre', soit dans le nom
                     $q->where('semestre', $this->selectedSemestre)
                       ->orWhere('nom', 'LIKE', '%' . $this->selectedSemestre . '%');
                })
                // Filtre par Groupe (Nom commençant par...)
                ->where('nom', 'LIKE', $this->selectedFiliereGroup . '%')
                ->orderBy('nom', 'asc')
                ->paginate(10);
        }

        return view('livewire.admin.gestion-filieres', [
            'filieres' => $filieres,
            'departements' => $departements,
            'niveaux' => NiveauCycle::cases(),
            'semestresDisponibles' => $semestresDisponibles,
            'availableGroups' => $this->availableGroups,
        ]);
    }
}
