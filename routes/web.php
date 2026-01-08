<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Livewire\Admin\GestionBordereaux;

// 1. Redirection Racine -> Login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function() {

    // ====================================================
    // ZONE GUEST (Non connecté)
    // ====================================================
    Route::middleware(['guest', 'preventBackHistory'])->group(function(){
        Route::controller(AuthController::class)->group(function(){
            Route::get('/login', 'loginForm')->name('login');
            Route::post('/login', 'loginHandler')->name('login_handler');
            Route::get('/register', 'registerForm')->name('register');
            Route::post('/register', 'registerHandler')->name('register_handler');
            Route::get('/verify-email/{token}', 'verifyEmailHandler')->name('verify_email');
            Route::get('/forgot-password', 'forgotForm')->name('forgot');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send_password_reset_link');
            Route::get('/password/reset/{token}', 'resetPasswordForm')->name('reset_password_form');
            Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset_password_handler');
        });
    });

    // ====================================================
    // ZONE AUTH (Connecté)
    // ====================================================
    Route::middleware(['auth', 'preventBackHistory'])->group(function(){

        // Routes gérées par AdminController
        Route::controller(AdminController::class)->group(function(){
            Route::post('/logout', 'logoutHandler')->name('logout');
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/change-profile-picture', 'changeProfilePicture')->name('change_profile_picture');

            // --- LA ROUTE MANQUANTE (TÉLÉCHARGEMENT WORD) ---
            // Nom final : admin.bordereau.download
            Route::get('/bordereau/{id}/word', 'downloadBordereau')->name('bordereau.download');
        });

        // --- BUREAU D'ORDRE (LIVEWIRE) ---
        Route::get('/bordereaux', function () {
            return view('back.pages.bordereaux_page', [
                'pageTitle' => 'Gestion des Bordereaux'
            ]);
        })->name('bordereaux');
        Route::get('/utilisateurs', function () {
    return view('back.pages.utilisateurs_page', [
        'pageTitle' => 'Gestion des Utilisateurs'
    ]);
})->name('utilisateurs');
    });
});
