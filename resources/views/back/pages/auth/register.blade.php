@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Inscription')
@section('content')

<div class="auth-header">
    <h2>Créer un compte</h2>
    <p>Rejoignez Sorties FSDM dès maintenant</p>
</div>

<x-form-alerts></x-form-alerts>

<form action="{{ route('admin.register_handler') }}" method="POST">
    @csrf

    {{-- Nom Complet --}}
    <div class="form-group">
        <label for="name">Nom complet</label>
        <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input
                type="text"
                id="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Votre nom complet"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>
        @error('name')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Username --}}
    <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <div class="input-wrapper">
            <i class="fas fa-id-card"></i>
            <input
                type="text"
                id="username"
                class="form-control @error('username') is-invalid @enderror"
                placeholder="Choisissez un nom d'utilisateur"
                name="username"
                value="{{ old('username') }}"
                required
            >
        </div>
        @error('username')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Email --}}
    <div class="form-group">
        <label for="email">Email</label>
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

    {{-- Confirm Password --}}
    <div class="form-group">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input
                type="password"
                id="password_confirmation"
                class="form-control"
                placeholder="••••••••••••"
                name="password_confirmation"
                required
            >
        </div>
    </div>

    {{-- Register Button --}}
    <button type="submit" class="btn-primary-modern" style="margin-top: 10px;">S'inscrire</button>

    {{-- Divider --}}
    <div class="divider">
        <span>Déjà inscrit ?</span>
    </div>

    {{-- Login Button --}}
    <a href="{{ route('admin.login') }}" class="btn-outline-modern">Se connecter</a>
</form>

@endsection
