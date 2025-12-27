@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')

@section('content')

    {{-- Charge le composant "cerveau" du Dashboard --}}
    @livewire('admin.dashboard')

@endsection
