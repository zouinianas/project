@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Connexion')
@section('content')

<div class="auth-header">
    <h2>Connexion</h2>
    <p>Accédez à votre compte Sorties FSDM</p>
</div>

<x-form-alerts></x-form-alerts>

<form action="{{ route('admin.login_handler') }}" method="POST">
    @csrf

    {{-- Email/Username --}}
    <div class="form-group">
        <label for="login_id">Email ou Nom d'utilisateur</label>
        <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input
                type="text"
                id="login_id"
                class="form-control @error('login_id') is-invalid @enderror"
                placeholder="votre email ou nom d'utilisateur"
                name="login_id"
                value="{{ old('login_id') }}"
                required
            >
        </div>
        @error('login_id')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Password --}}
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input
                type="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••••••"
                name="password"
                required
            >
        </div>
        @error('password')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Remember & Forgot Password --}}
    <div class="custom-checkbox-modern mb-20">
        <input type="checkbox" id="rememberMe" name="remember">
        <label for="rememberMe">Se souvenir de moi</label>
    </div>

    <div style="text-align: right; margin-bottom: 20px;">
        <a href="{{ route('admin.forgot') }}" class="auth-link" style="font-size: 14px;">Mot de passe oublié ?</a>
    </div>

    {{-- Sign In Button --}}
    <button type="submit" class="btn-primary-modern">Connexion</button>

    {{-- Divider --}}
    <div class="divider">
        <span>Pas encore inscrit ?</span>
    </div>

    {{-- Sign Up Button --}}
    <a href="{{ route('admin.register') }}" class="btn-outline-modern">Créer un compte</a>
</form>

@endsection
