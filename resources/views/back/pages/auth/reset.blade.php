@extends('back.layout.auth-layout')
@section('pageTitle', 'Réinitialiser le mot de passe')
@section('content')

<div class="auth-header">
    <h2>Nouveau mot de passe</h2>
    <p>Choisissez un nouveau mot de passe sécurisé</p>
</div>

<form action="{{ route('admin.reset_password_handler', ['token' => $token]) }}" method="POST">
    <x-form-alerts></x-form-alerts>
    @csrf

    {{-- Nouveau mot de passe --}}
    <div class="form-group">
        <label>Nouveau mot de passe</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input
                type="password"
                name="new_password"
                placeholder="Minimum 8 caractères"
                required
                autofocus
            >
        </div>
        @error('new_password')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Confirmation --}}
    <div class="form-group">
        <label>Confirmer le mot de passe</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input
                type="password"
                name="new_password_confirmation"
                placeholder="Répétez votre nouveau mot de passe"
                required
            >
        </div>
        @error('new_password_confirmation')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    {{-- Conseils de sécurité --}}
    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px; margin: 20px 0; font-size: 13px; color: #075985;">
        <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
        <strong>Conseil :</strong> Utilisez au moins 8 caractères avec des lettres, chiffres et symboles.
    </div>

    {{-- Bouton Réinitialiser --}}
    <button type="submit" class="btn-primary-modern">
        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
        Réinitialiser mon mot de passe
    </button>
</form>

@endsection
