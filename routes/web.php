<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
// IMPORTS POUR LE SCRIPT DE CORRECTION
use App\Models\Filiere;
use App\Enums\Semestre;

// 1. Redirection de la racine
Route::get('/', function () {
    // Si l'utilisateur va sur la racine, on le renvoie vers le login
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function() {

    // ====================================================
    // ZONE GUEST (Utilisateurs NON connectés)
    // Ici se trouvent Login, Register, Forgot Password
    // ====================================================
    Route::middleware(['guest', 'preventBackHistory'])->group(function(){
        Route::controller(AuthController::class)->group(function(){
            // Login
            Route::get('/login', 'loginForm')->name('login');
            Route::post('/login', 'loginHandler')->name('login_handler');

            // Register (Sign Up)
            Route::get('/register', 'registerForm')->name('register');
            Route::post('/register', 'registerHandler')->name('register_handler');

            // Vérification Email
            Route::get('/verify-email/{token}', 'verifyEmailHandler')->name('verify_email');

            // Forgot Password
            Route::get('/forgot-password', 'forgotForm')->name('forgot');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send_password_reset_link');
            Route::get('/password/reset/{token}', 'resetForm')->name('reset_password_form');
            Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset_password_handler');
        });
    });

    // ====================================================
    // ZONE AUTH (Utilisateurs CONNECTÉS seulement)
    // Ici se trouvent le Dashboard et la gestion
    // ====================================================
    Route::middleware(['auth', 'preventBackHistory'])->group(function(){

        // 2. Dashboard et Gestion (Utilise AdminController)
        Route::controller(AdminController::class)->group(function(){

            // --- CORRECTION : La route logout doit être ici car logoutHandler est dans AdminController ---
            Route::post('/logout', 'logoutHandler')->name('logout');

            // Dashboard & Compte
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');
            Route::get('/profile', 'profileView')->name('profile');

            // --- PAGES DE GESTION ---
            Route::get('/departements', 'departementsPage')->name('departements');
            Route::get('/filieres', 'filieresPage')->name('filieres');
            Route::get('/modules', 'modulesPage')->name('modules');
            Route::get('/personnels', 'personnelsPage')->name('personnels');
            Route::get('/destinations', 'destinationsPage')->name('destinations');

            // --- IMPRESSION & ARCHIVES ---
            Route::get('/print-sortie/{id}', 'printOrdreMission')->name('print_sortie');
            Route::get('/print-ordre-libre/{id}', 'printOrdreLibre')->name('print_ordre_libre');
            Route::get('/archive/{year}', 'downloadYearArchive')->name('download_archive');

        });
    });
});

// ============================================================
// ROUTE TEMPORAIRE : CORRECTION DES SEMESTRES
// (À conserver tant que vous n'avez pas fini la correction)
// ============================================================
Route::get('/fix-semestres', function () {
    $filieres = Filiere::all();
    $count = 0;

    foreach ($filieres as $filiere) {
        $nom = strtoupper($filiere->nom);
        foreach (Semestre::cases() as $sem) {
            if (str_contains($nom, $sem->value)) {
                $filiere->update(['semestre' => $sem]);
                $count++;
                break;
            }
        }
    }

    return "<h1 style='color:green; text-align:center; margin-top:50px;'>Succès !</h1>
            <p style='text-align:center;'>$count filières ont été mises à jour.</p>";
});
