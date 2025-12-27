@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Gérer les Cycles/Filières')

@section('content')

    {{-- Charge le composant "cerveau" --}}
    @livewire('admin.gestion-filieres')

@endsection
