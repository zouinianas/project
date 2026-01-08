<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// 1. Redirection de la racine vers le login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function() {

    // ====================================================
    // ZONE GUEST (Utilisateurs NON connectés)
    // ====================================================
    Route::middleware(['guest', 'preventBackHistory'])->group(function(){
        Route::controller(AuthController::class)->group(function(){
            // Login
            Route::get('/login', 'loginForm')->name('login');
            Route::post('/login', 'loginHandler')->name('login_handler');

            // Register (Inscription)
            Route::get('/register', 'registerForm')->name('register');
            Route::post('/register', 'registerHandler')->name('register_handler');

            // Vérification Email
            Route::get('/verify-email/{token}', 'verifyEmailHandler')->name('verify_email');

            // Mot de passe oublié
            Route::get('/forgot-password', 'forgotForm')->name('forgot');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send_password_reset_link');
            Route::get('/password/reset/{token}', 'resetPasswordForm')->name('reset_password_form');
            Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset_password_handler');
        });
    });

    // ====================================================
    // ZONE AUTH (Utilisateurs Connectés)
    // ====================================================
    Route::middleware(['auth', 'preventBackHistory'])->group(function(){
        Route::controller(AdminController::class)->group(function(){

            // Déconnexion
            Route::post('/logout', 'logoutHandler')->name('logout');

            // Dashboard (Accueil)
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');

            // Profil
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/change-profile-picture', 'changeProfilePicture')->name('change_profile_picture');

            // ---------------------------------------------------
            // NOS NOUVELLES ROUTES (Bureau d'Ordre)
            // ---------------------------------------------------

            // 1. La page principale des bordereaux
            Route::get('/bordereaux', function () {
                return view('back.pages.bordereaux_page', ['pageTitle' => 'Bureau d\'Ordre - Départs']);
            })->name('bordereaux');

            // 2. La route pour télécharger le Word (sera codée plus tard)
            Route::get('/bordereaux/download/{id}', [AdminController::class, 'downloadBordereau'])->name('bordereaux.download');

        });
    });
});
