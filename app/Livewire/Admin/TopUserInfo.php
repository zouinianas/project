<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TopUserInfo extends Component
{

    protected $listeners = [
        'updateTopUserInfo'=>'$refresh'
    ];
    public function render()
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            abort(403, "L'utilisateur n'est pas connecté.");
        }

        return view('livewire.admin.top-user-info', [
            'user' => User::findOrFail(Auth::id()) // Utilisation de Auth::id() pour éviter les erreurs
        ]);
    }
}
