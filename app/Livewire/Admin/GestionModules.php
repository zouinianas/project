<?php

namespace App\Livewire\Admin;

use App\Models\Module;
use App\Models\Departement;
use App\Models\Filiere;
use App\Enums\NiveauCycle;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionModules extends Component
{
    use WithPagination;

    // --- NAVIGATION ---
    public $selectedDepartementId = null;
    public $selectedNiveau = null;
    public $selectedFiliereGroup = null; // ex: "MQL", "Biologie" (Nom de la spécialité)
    public $selectedSemestre = null;     // ex: "S1"

    // --- FORMULAIRE ---
    public $module_id;
    public $nom;
    public $filiere_id;
    public $departement_id_form;
    public $filieres_form = [];
    public $isUpdateMode = false;

    // --- NAVIGATION LOGIQUE ---

    public function selectDepartement($id)
    {
        $this->resetNavigation();
        $this->selectedDepartementId = $id;

        // Par défaut, on se met sur DEUG 1ère année
        $this->selectedNiveau = NiveauCycle::DEUG_A1->value;
        $this->resetPage();
    }

    public function selectNiveau($niveauValue)
    {
        $this->selectedNiveau = $niveauValue;
        $this->selectedFiliereGroup = null; // On reset la spécialité quand on change de cycle
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

    // --- COMPUTED PROPERTIES (Pour l'affichage propre) ---

    /**
     * Récupère la liste des "Spécialités" uniques pour le département et le niveau actuel.
     * Ex: Pour Master, ça va renvoyer ["MQL", "BIOMSSI", "M2SD"...] en se basant sur les filières.
     */
    public function getAvailableGroupsProperty()
    {
        if (!$this->selectedDepartementId || !$this->selectedNiveau) {
            return [];
        }

        // On récupère toutes les filières de ce niveau/département
        $filieres = Filiere::where('departement_id', $this->selectedDepartementId)
                           ->where('niveau', $this->selectedNiveau)
                           ->get();

        $groups = [];

        foreach ($filieres as $f) {
            // Nettoyage du nom : on enlève " - S1", " - S2", etc. pour garder la base "MQL"
            // Regex : on cherche " - S" suivi d'un chiffre et on coupe avant
            $baseName = preg_replace('/ - S[0-9]+.*$/', '', $f->nom);

            // On enlève aussi les parenthèses de fin si besoin ex: " (Physique)"
            // $baseName = preg_replace('/ \([a-zA-Z]+\)$/', '', $baseName);

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
                Rule::unique('modules')->ignore($this->module_id)->where(function ($query) {
                    return $query->where('filiere_id', $this->filiere_id);
                })
            ],
            'filiere_id' => 'required|exists:filieres,id',
            'departement_id_form' => 'required|exists:departements,id',
        ];
    }

    public function updatedDepartementIdForm()
    {
        if ($this->departement_id_form) {
            $this->filieres_form = Filiere::where('departement_id', $this->departement_id_form)
                                     ->orderBy('niveau')
                                     ->orderBy('nom')
                                     ->get();
        } else {
            $this->filieres_form = [];
        }
        $this->reset('filiere_id');
    }

    public function openModalCreate()
    {
        $this->isUpdateMode = false;
        $this->resetForm();
        if($this->selectedDepartementId) {
            $this->departement_id_form = $this->selectedDepartementId;
            $this->updatedDepartementIdForm();
        }
        $this->dispatch('showModuleModal');
    }

    public function openModalEdit(Module $module)
    {
        $this->isUpdateMode = true;
        $this->module_id = $module->id;
        $this->nom = $module->nom;
        $this->filiere_id = $module->filiere_id;

        if ($module->filiere) {
            $this->departement_id_form = $module->filiere->departement_id;
            $this->updatedDepartementIdForm();
        }
        $this->dispatch('showModuleModal');
    }

    public function closeModal()
    {
        $this->dispatch('hideModuleModal');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nom', 'filiere_id', 'module_id', 'departement_id_form']);
        $this->resetErrorBag();
        $this->filieres_form = [];
    }

    public function save()
    {
        $this->validate();
        Module::updateOrCreate(
            ['id' => $this->module_id],
            ['nom' => $this->nom, 'filiere_id' => $this->filiere_id]
        );
        $message = $this->isUpdateMode ? 'Module mis à jour.' : 'Module créé.';
        $this->dispatch('showToastr', ['type' => 'success', 'message' => $message]);
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('showDeleteConfirmation', $id);
    }

    public function delete($id)
    {
        Module::destroy($id);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Module supprimé.']);
    }

    // --- RENDER ---

    public function render()
    {
        $departements = Departement::orderBy('nom', 'asc')->get();
        $modules = [];
        $semestresDisponibles = [];

        // Gestion des semestres pour l'affichage des boutons
        if($this->selectedNiveau) {
            $currentNiveauEnum = NiveauCycle::tryFrom($this->selectedNiveau);
            if($currentNiveauEnum) {
                $semestresDisponibles = $currentNiveauEnum->semestres();
            }
        }

        // --- REQUÊTE PRINCIPALE ---
        if ($this->selectedDepartementId && $this->selectedFiliereGroup && $this->selectedSemestre) {

            $modules = Module::with(['filiere.departement'])
                ->whereHas('filiere', function ($query) {
                    $query->where('departement_id', $this->selectedDepartementId);

                    // Filtre par Niveau
                    $query->where('niveau', $this->selectedNiveau);

                    // Filtre par Semestre (Via colonne si dispo, sinon LIKE pour compatibilité)
                    $query->where(function($q) {
                        $q->where('semestre', $this->selectedSemestre)
                          ->orWhere('nom', 'LIKE', '%' . $this->selectedSemestre . '%');
                    });

                    // Filtre par "Spécialité" (Nom de groupe)
                    // On cherche les filières qui commencent par le nom du groupe (ex: "MQL%")
                    $query->where('nom', 'LIKE', $this->selectedFiliereGroup . '%');
                })
                ->orderBy('nom', 'asc')
                ->paginate(10);
        }

        return view('livewire.admin.gestion-modules', [
            'modules' => $modules,
            'departements' => $departements,
            'niveaux' => NiveauCycle::cases(),
            'semestresDisponibles' => $semestresDisponibles,
            'availableGroups' => $this->availableGroups, // La liste calculée des masters/spécialités
        ]);
    }
}
