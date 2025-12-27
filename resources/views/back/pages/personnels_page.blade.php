@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Gérer le Personnel')

@section('content')

    {{-- Charge le composant "cerveau" --}}
    @livewire('admin.gestion-personnels')

@endsection
