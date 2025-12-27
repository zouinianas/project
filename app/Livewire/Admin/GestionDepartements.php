<?php

namespace App\Livewire\Admin;

use App\Models\Departement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionDepartements extends Component
{
    use WithPagination;

    // Propriétés du formulaire
    public $nom;
    public $couleur;
    public $departement_id;

    // Propriétés de l'état
    public $isUpdateMode = false;

    // Palette de couleurs (Vous pouvez en ajouter d'autres ici)
    public $palette = [
        '#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e',
        '#16a085', '#27ae60', '#2980b9', '#8e44ad', '#2c3e50',
        '#f1c40f', '#e67e22', '#e74c3c', '#ecf0f1', '#95a5a6',
        '#f39c12', '#d35400', '#c0392b', '#bdc3c7', '#7f8c8d',
        '#55efc4', '#81ecec', '#74b9ff', '#a29bfe', '#dfe6e9',
        '#00b894', '#00cec9', '#0984e3', '#6c5ce7', '#b2bec3',
        '#ffeaa7', '#fab1a0', '#ff7675', '#fd79a8', '#636e72',
        '#fdcb6e', '#e17055', '#d63031', '#e84393', '#2d3436'
    ];

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
                Rule::unique('departements')->ignore($this->departement_id)
            ],
            'couleur' => 'required|string|max:7',
        ];
    }

    /**
     * NOUVEAU : Calcule les couleurs disponibles
     * (Palette totale - Couleurs utilisées par les autres départements)
     */
    public function getCouleursDisponiblesProperty()
    {
        // 1. Récupérer toutes les couleurs utilisées dans la base
        $couleursUtilisees = Departement::pluck('couleur')->toArray();

        // 2. Si on est en mode Modification, on retire la couleur actuelle de ce département
        // de la liste des "interdits", pour pouvoir garder la même couleur.
        if ($this->isUpdateMode && $this->departement_id) {
            $deptActuel = Departement::find($this->departement_id);
            if ($deptActuel) {
                $couleursUtilisees = array_diff($couleursUtilisees, [$deptActuel->couleur]);
            }
        }

        // 3. Retourner la différence (Palette - Utilisées)
        return array_values(array_diff($this->palette, $couleursUtilisees));
    }

    /**
     * NOUVEAU : Sélectionne une couleur au clic
     */
    public function selectCouleur($color)
    {
        $this->couleur = $color;
    }

    public function openModalCreate()
    {
        $this->isUpdateMode = false;
        $this->resetForm();
        $this->dispatch('showDepartementModal');
    }

    public function openModalEdit(Departement $departement)
    {
        $this->isUpdateMode = true;
        $this->departement_id = $departement->id;
        $this->nom = $departement->nom;
        $this->couleur = $departement->couleur;
        $this->dispatch('showDepartementModal');
    }

    public function closeModal()
    {
        $this->dispatch('hideDepartementModal');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nom', 'couleur', 'departement_id']);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        Departement::updateOrCreate(
            ['id' => $this->departement_id],
            [
                'nom' => $this->nom,
                'couleur' => $this->couleur,
            ]
        );

        $message = $this->isUpdateMode ? 'Département mis à jour avec succès.' : 'Département créé avec succès.';

        $this->dispatch('showToastr', ['type' => 'success', 'message' => $message]);
        $this->closeModal(); // Fermer le modal après sauvegarde
    }

    public function confirmDelete($id)
    {
        $this->dispatch('showDeleteConfirmation', $id);
    }

    public function delete($id)
    {
        Departement::destroy($id);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Département supprimé avec succès.']);
    }

    public function render()
    {
        return view('livewire.admin.gestion-departements', [
            'departements' => Departement::orderBy('nom', 'asc')->paginate(10)
        ]);
    }
}
