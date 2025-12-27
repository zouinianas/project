@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Gérer les Destinations')

@section('content')

    {{-- Charge le composant "cerveau" --}}
    @livewire('admin.gestion-destinations')

@endsection
