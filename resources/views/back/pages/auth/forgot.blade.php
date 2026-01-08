@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Réinitialiser le mot de passe')
@section('content')

<div class="auth-header">
    <h2>Mot de passe oublié</h2>
    <p>Entrez votre email pour réinitialiser votre mot de passe</p>
</div>

<x-form-alerts></x-form-alerts>

<form action="{{ route('admin.send_password_reset_link') }}" method="POST">
    @csrf

    {{-- Email --}}
    <div class="form-group">
        <label for="email">Adresse Email</label>
        <div class="input-wrapper">
            <i class="fas fa-envelope"></i>
            <input
                type="email"
                id="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="votre@email.com"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>
        @error('email')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Submit Button --}}
    <button type="submit" class="btn-primary-modern">Envoyer le lien de réinitialisation</button>

    {{-- Divider --}}
    <div class="divider">
        <span>Retour à la connexion</span>
    </div>

    {{-- Login Button --}}
    <a href="{{ route('admin.login') }}" class="btn-outline-modern">Se connecter</a>
</form>

@endsection
