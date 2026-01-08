<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout; // On garde cet import
use App\Models\CourrierDepart;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GestionBordereaux extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $courrier_id, $date_depart, $destinataire, $objet, $nombre_pieces = 1, $observation;
    public $search = '';
    public $isEditMode = false;

    protected $rules = [
        'date_depart' => 'required|date',
        'destinataire' => 'required|string|max:255',
        'objet' => 'required|string',
        'nombre_pieces' => 'integer|min:0',
        'observation' => 'nullable|string',
    ];

    public function mount()
    {
        $this->date_depart = Carbon::now()->format('Y-m-d');
    }

    public function resetForm()
    {
        $this->reset(['date_depart', 'destinataire', 'objet', 'nombre_pieces', 'observation', 'courrier_id', 'isEditMode']);
        $this->date_depart = Carbon::now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->dispatch('showModal');
    }

    public function edit($id)
    {
        $courrier = CourrierDepart::findOrFail($id);

        $this->courrier_id = $courrier->id;
        $this->date_depart = $courrier->date_depart ? $courrier->date_depart->format('Y-m-d') : null;
        $this->destinataire = $courrier->destinataire;
        $this->objet = $courrier->objet;
        $this->nombre_pieces = $courrier->nombre_pieces;
        $this->observation = $courrier->observation;

        $this->isEditMode = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        $this->validate();
        $anneeChoisie = Carbon::parse($this->date_depart)->year;

        if ($this->isEditMode) {
            $courrier = CourrierDepart::findOrFail($this->courrier_id);
            $courrier->update([
                'date_depart' => $this->date_depart,
                'annee' => $anneeChoisie,
                'destinataire' => $this->destinataire,
                'objet' => $this->objet,
                'nombre_pieces' => $this->nombre_pieces,
                'observation' => $this->observation,
            ]);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Bordereau mis à jour avec succès.']);
        } else {
            $dernier = CourrierDepart::where('annee', $anneeChoisie)->max('numero_ordre');
            $nouveauNumero = $dernier ? $dernier + 1 : 1;

            CourrierDepart::create([
                'numero_ordre' => $nouveauNumero,
                'annee' => $anneeChoisie,
                'date_depart' => $this->date_depart,
                'destinataire' => $this->destinataire,
                'objet' => $this->objet,
                'nombre_pieces' => $this->nombre_pieces,
                'observation' => $this->observation,
                'user_id' => Auth::id(),
            ]);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Nouveau Bordereau N°'.$nouveauNumero.' créé.']);
        }

        $this->dispatch('hideModal');
        $this->resetForm();
    }

    public function delete($id)
    {
        CourrierDepart::find($id)->delete();
        $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Bordereau supprimé.']);
    }

    // --- CORRECTION ICI : Utilisation de l'Attribut au lieu de la chaîne de méthodes ---
    #[Layout('back.layout.pages-layout', ['pageTitle' => 'Gestion des Bordereaux'])]
    public function render()
    {
        $courriers = CourrierDepart::where(function($query){
                $query->where('destinataire', 'like', '%'.$this->search.'%')
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
