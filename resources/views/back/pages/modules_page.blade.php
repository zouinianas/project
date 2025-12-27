@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Gérer les Modules')

@section('content')

    {{-- Charge le composant "cerveau" --}}
    @livewire('admin.gestion-modules')

@endsection
