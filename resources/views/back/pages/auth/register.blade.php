@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Register')
@section('content')
<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Register</h2>
    </div>

    <form action="{{ route('admin.register_handler') }}" method="POST">

        <x-form-alerts></x-form-alerts>
        @csrf

        {{-- Nom --}}
        <div class="input-group custom mb-1">
            <input type="text" class="form-control form-control-lg" placeholder="Nom complet" name="name" value="{{ old('name') }}">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        @error('name')
            <span class="text-danger ml-1">{{ $message }}</span>
        @enderror

        {{-- Username --}}
        <div class="input-group custom mb-1 mt-2">
            <input type="text" class="form-control form-control-lg" placeholder="Username" name="username" value="{{ old('username') }}">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user"></i></span>
            </div>
        </div>
        @error('username')
            <span class="text-danger ml-1">{{ $message }}</span>
        @enderror

        {{-- Email --}}
        <div class="input-group custom mb-1 mt-2">
            <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="{{ old('email') }}">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-email"></i></span>
            </div>
        </div>
        @error('email')
            <span class="text-danger ml-1">{{ $message }}</span>
        @endError

        {{-- Password --}}
        <div class="input-group custom mb-1 mt-2">
            <input type="password" class="form-control form-control-lg" placeholder="Mot de passe" name="password">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>
        @error('password')
            <span class="text-danger ml-1">{{ $message }}</span>
        @endError

        {{-- Confirm Password --}}
        <div class="input-group custom mb-1 mt-2">
            <input type="password" class="form-control form-control-lg" placeholder="Confirmer le mot de passe" name="password_confirmation">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>
        {{-- (Pas besoin d'erreur ici, l'erreur 'password' gère la confirmation) --}}


        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Register">
                </div>
                <div class="font-16 weight-600 text-center" data-color="#707373" style="margin-top: 15px;">
                    Déjà un compte ? <a href="{{ route('admin.login') }}">Login</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
