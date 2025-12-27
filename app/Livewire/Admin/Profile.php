<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Helpers\CMail;
// use App\Models\UserSocialLink; // Supprimé

class Profile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];

    // --- Champs du formulaire simplifiés ---
    public $name, $email, $username;

    public $current_password, $new_password, $new_password_confirmation;

    // --- Les listeners et les propriétés de liens sociaux sont supprimés ---

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = request('tab') ? request('tab') : $this->tabname;

        $user = User::findOrFail(Auth::id());
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        // --- bio et social_links supprimés ---
    }

    public function updatePersonalDetails()
    {
        $user = User::findOrFail(Auth::id());

        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username,' . $user->id,
            // (Nous ne permettons pas de changer l'e-mail pour l'instant pour rester simple)
        ]);

        $updated = $user->update([
            'name' => $this->name,
            'username' => $this->username,
            // --- bio supprimé ---
        ]);

        if ($updated) {
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Vos informations personnelles ont été mises à jour.'
            ]);
            $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Quelque chose s\'est mal passé.'
            ]);
        }
    }

    public function updatePassword()
    {
        $user = User::findOrFail(Auth::id());

        $this->validate([
            'current_password' => [
                'required',
                'min:5',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Votre mot de passe actuel est incorrect.');
                    }
                }
            ],
            'new_password' => 'required|min:5|confirmed',
        ]);

        $updated = $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        if ($updated) {
            $data = array(
                'user' => $user,
                'new_password' => $this->new_password
            );

            $mail_body = view('email-templates.password-changes-template', $data)->render();
            $mail_config = array(
                'recipient_address' => $user->email,
                'recipient_name' => $user->name,
                'subject' => 'Votre mot de passe a été changé',
                'body' => $mail_body
            );

            CMail::send($mail_config);
            Auth::logout();
            Session::flash('info', 'Votre mot de passe a été mis à jour. Veuillez vous reconnecter.');
            $this->redirectRoute('admin.login');

        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Quelque chose s\'est mal passé.'
            ]);
        }
    }

    // --- La fonction updateSocialLinks() est SUPPRIMÉE ---

    public function render()
    {
        if (!Auth::check()) {
            abort(403, "L'utilisateur n'est pas connecté.");
        }
        return view('livewire.admin.profile', [
            'user' => Auth::user()
        ]);
    }
}
