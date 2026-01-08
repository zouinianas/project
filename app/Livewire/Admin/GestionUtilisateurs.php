<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class GestionUtilisateurs extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $email, $password, $user_id;
    public $search = '';
    public $isEditMode = false;

    // Règles de validation
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => $this->isEditMode ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    // Réinitialiser le formulaire
    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'user_id', 'isEditMode']);
        $this->resetValidation();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->dispatch('showModal');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isEditMode = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditMode) {
            $user = User::findOrFail($this->user_id);

            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            // On ne change le mot de passe que s'il est rempli
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Utilisateur mis à jour.']);

        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Nouvel utilisateur créé.']);
        }

        $this->dispatch('hideModal');
        $this->resetForm();
    }

    public function delete($id)
    {
        // Empêcher de se supprimer soi-même
        if($id == Auth::id()){
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Vous ne pouvez pas supprimer votre propre compte.']);
            return;
        }

        User::find($id)->delete();
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Utilisateur supprimé.']);
    }

    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
                     ->orWhere('email', 'like', '%'.$this->search.'%')
                     ->paginate(10);

        return view('livewire.admin.gestion-utilisateurs', [
            'users' => $users
        ]);
    }
}
