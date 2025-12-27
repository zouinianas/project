<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Redirection si l'utilisateur est déjà authentifié
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('admin.dashboard');
        });

        // Redirection si l'utilisateur n'est pas authentifié
        Authenticate::redirectUsing(function () {
            Session::flash('fail', 'You must be logged in to access admin area. Please login to continue.');
            return route('admin.login'); // Retourne uniquement le chemin de la route
        });
    }
}
