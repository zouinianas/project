@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Gérer les Départements')

@section('content')

    {{-- Ceci charge les Fichiers 1 et 2 --}}
    @livewire('admin.gestion-departements')

@endsection
