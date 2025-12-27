<?php

namespace App\Livewire\Admin;

use App\Models\Destination;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionDestinations extends Component
{
    use WithPagination;

    // Propriétés du formulaire
    public $nom;
    public $destination_id;

    // Propriétés de l'état
    public $isUpdateMode = false;

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
                Rule::unique('destinations')->ignore($this->destination_id)
            ],
        ];
    }

    /**
     * Ouvre le modal pour une création
     */
    public function openModalCreate()
    {
        $this->isUpdateMode = false;
        $this->resetForm();
        $this->dispatch('showDestinationModal');
    }

    /**
     * Ouvre le modal pour une édition
     */
    public function openModalEdit(Destination $destination)
    {
        $this->isUpdateMode = true;
        $this->destination_id = $destination->id;
        $this->nom = $destination->nom;
        $this->dispatch('showDestinationModal');
    }

    /**
     * Ferme le modal
     */
    public function closeModal()
    {
        $this->dispatch('hideDestinationModal');
        $this->resetForm();
    }

    /**
     * Réinitialise les champs du formulaire
     */
    private function resetForm()
    {
        $this->reset(['nom', 'destination_id']);
        $this->resetErrorBag();
    }

    /**
     * Logique de sauvegarde (Création ou Mise à jour)
     */
    public function save()
    {
        $this->validate();

        Destination::updateOrCreate(
            ['id' => $this->destination_id],
            [ 'nom' => $this->nom ]
        );

        $message = $this->isUpdateMode ? 'Destination mise à jour avec succès.' : 'Destination créée avec succès.';
        $this->dispatch('showToastr', ['type' => 'success', 'message' => $message]);

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('showDeleteConfirmation', $id);
    }

    /**
     * CORRECTION APPLIQUÉE : Utilise ::destroy($id)
     */
    public function delete($id)
    {
        Destination::destroy($id);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Destination supprimée avec succès.']);
    }

    /**
     * Affiche la vue
     */
    public function render()
    {
        return view('livewire.admin.gestion-destinations', [
            'destinations' => Destination::orderBy('nom', 'asc')->paginate(10)
        ]);
    }
}
