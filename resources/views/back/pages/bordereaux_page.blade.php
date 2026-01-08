@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Bureau d\'Ordre')

@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Bureau d'Ordre - Départs</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registre des Départs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Charge le composant Livewire --}}
    @livewire('admin.gestion-bordereaux')

@endsection
