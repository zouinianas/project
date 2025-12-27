<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\UserStatus; // Ensure this is correctly imported
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Helpers\CMail;


class AuthController extends Controller
{
    public function loginForm(Request $request){
        $data = [
            'pageTitle' => 'Login'
        ];
        return view('back.pages.auth.login', $data);
    }

    public function forgotForm(Request $request){
        $data = [
            'pageTitle'=> 'Forgot Password'
        ];
        return view('back.pages.auth.forgot', $data);
    }

    public function loginHandler(Request $request){
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email'  :  'username';

        if($fieldType == 'email'){
            $request->validate([
                'login_id'=>'required|email|exists:users,email',
                'password'=> 'required|min:5'
            ],[
                'login_id.required'=> 'Enter your email or username',
                'login_id.email'=> 'Invalid email address',
                'login_id.exists'=> 'No account found for this email'
            ]);
        }else{
            $request->validate([
                'login_id'=> 'required|exists:users,username',
                'password'=> 'required|min:5'
            ],[
                'login_id.required'=> 'Enter your username or email',
                'login_id.exists'=> 'No account found for this username'
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password,
        );

        if(Auth::attempt($creds)){
            $user = Auth::user(); // Correctly get the authenticated user

            if($user->status == UserStatus::Inactive){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail','Your account is currently inactive. Please, contact support at (bellmirossama@gmail.com) for further assistance.');
            }

            if($user->status == UserStatus::Pending){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // --- MODIFIÉ ---
                // Message plus précis pour l'utilisateur
                return redirect()->route('admin.login')->with('fail','Votre compte est en attente. Veuillez vérifier votre boîte e-mail pour le lien de vérification.');
            }

            // Redirect user to dashboard
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.login')->withInput()->with('fail','Incorrect password.');
        }
    }

    public function sendPasswordResetLink(Request $request){
        // Valider les données du formulaire
        $request->validate([
            'email' => 'required|email|exists:users,email' // Correction : 'users' au lieu de 'user'
        ], [
            'email.required' => 'The :attribute is required',
            'email.email' => 'Invalid email address',
            'email.exists' => 'We can not find a user with this email address'
        ]);

        $user = User::where('email', $request->email)->first();

        $token = base64_encode(Str::random(64));

        $oldToken = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if($oldToken){
            DB::table('password_reset_tokens')->where('email', $user->email)->update([
                'token'=> $token,
                'created_at'=>Carbon::now()
            ]);
        }else{

            DB::table('password_reset_tokens')->insert([
                'email'=> $user->email,
                'token'=> $token,
                'created_at'=>Carbon::now()
            ]);
        }

        $actionLink = route('admin.reset_password_form',['token'=>$token]);

        $data = array(
            'actionlink'=> $actionLink,
            'user'=>$user
        );

        $mail_body = view('email-templates.forgot-template', $data)->render();

        $mailConfig = array(
            'recipient_address'=> $user->email,
            'recipient_name'=> $user->name,
            'subject'=> 'Reset Password',
            'body'=> $mail_body
        );

        if(CMail::send($mailConfig)){
            return redirect()->route('admin.forgot')->with('success','We have e-mailed your password reset link');

        }else {
            return redirect()->route('admin.forgot')->with('fail','Something went wrong. Resetting password link not sent. Try again later');
        }

    }

    public function resetForm(Request $request, $token = null){
        $isTokenExists = DB::table('password_reset_tokens')->where('token',$token)->first();
        if(!$isTokenExists){
            return redirect()->route('admin.forgot')->with('fail','Invalid token. Request another reset password link.');
        } else {
            //Check if Token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s',$isTokenExists->created_at)->diffInMinutes(Carbon::now());

            if($diffMins > 15){
                return redirect()->route('admin.forgot')->with('fail','The password reset link you clicked has expired. Please request a new link.');
            }
            $data = [
                'pageTitle'=>'Reset Password',
                'token'=>$token
            ];

            return view('back.pages.auth.reset', $data);
        }
    }

    public function resetPasswordHandler(Request $request) {
        // Validation des entrées
        $request->validate([
            'token' => 'required',
            'new_password' => 'required|min:5|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required',
        ]);

        // Vérification du token
        $dbToken = DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if (!$dbToken) {
            return redirect()->route('admin.forgot')->with('fail', 'Invalid or expired token.');
        }

        // Récupération de l'utilisateur
        $user = User::where('email', $dbToken->email)->first();
        if (!$user) {
            return redirect()->route('admin.forgot')->with('fail', 'User not found.');
        }

        // Mise à jour du mot de passe
        User::where('email', $dbToken->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Préparation de l'email
        $data = [
            'user' => $user,
            'new_password' => $request->new_password
        ];

        $mail_body = view('email-templates.password-changes-template', $data)->render();

        $mailConfig = [
            'recipient_address' => $user->email, // Correction ici
            'recipient_name' => $user->name, // Correction ici
            'subject' => 'Password Changed',
            'body' => $mail_body
        ];


        // Envoi de l'email
        if (CMail::send($mailConfig)) {
            // Suppression du token après utilisation
            DB::table('password_reset_tokens')->where([
                'email' => $dbToken->email,
                'token' => $dbToken->token
            ])->delete();

            return redirect()->route('admin.login')->with('success', 'Your password has been changed successfully.');
        } else {
            return redirect()->route('admin.reset_password_form', ['token' => $dbToken->token])
                ->with('fail', 'Something went wrong. Try again later.');
        }
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function registerForm(Request $request){
        $data = [
            'pageTitle' => 'Register'
        ];
        // Assurez-vous que cette vue existe
        return view('back.pages.auth.register', $data);
    }

    /**
     * Gère la soumission du formulaire d'inscription
     *
     * // --- CETTE FONCTION EST MAINTENANT MODIFIÉE ---
     */
    public function registerHandler(Request $request){

        // Validation
        $request->validate([
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:5|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        // --- NOUVEAU : Générer un token de vérification
        $verificationToken = base64_encode(Str::random(64));

        // Création de l'utilisateur
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->verification_token = $verificationToken; // <-- NOUVEAU
        // Le statut 'pending' et le type 'admin' sont gérés par les valeurs par défaut de la BDD
        $user->save();

        // --- NOUVEAU : Envoyer l'e-mail de vérification
        $actionLink = route('admin.verify_email', ['token' => $verificationToken]);
        $data = [
            'actionlink' => $actionLink,
            'user' => $user
        ];
        $mail_body = view('email-templates.verify-account-template', $data)->render();
        $mailConfig = [
            'recipient_address' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Vérifiez votre compte',
            'body' => $mail_body
        ];

        // Envoyer l'email
        CMail::send($mailConfig);

        // Redirection vers le login avec un message de succès
        return redirect()->route('admin.login')
                         ->with('success', 'Compte créé avec succès. Un e-mail de vérification a été envoyé à votre adresse.');
    }


    /**
     * // --- CETTE FONCTION EST NOUVELLE ---
     * Gère le clic sur le lien de vérification de l'e-mail
     */
    public function verifyEmailHandler(Request $request, $token = null)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            // Si le token n'existe pas
            return redirect()->route('admin.login')
                             ->with('fail', 'Token de vérification invalide ou expiré.');
        }

        // Si l'utilisateur est trouvé
        $user->status = UserStatus::Active; // Change le statut
        $user->email_verified_at = Carbon::now(); // (Optionnel) Marque l'e-mail comme vérifié
        $user->verification_token = null; // (Important) Supprime le token pour qu'il ne soit pas réutilisé
        $user->save();

        // Connecter l'utilisateur automatiquement
        Auth::login($user);

        // Rediriger vers le dashboard
        return redirect()->route('admin.dashboard')
                         ->with('success', 'Votre compte a été vérifié avec succès. Vous êtes maintenant connecté.');
    }
}
