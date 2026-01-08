@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Utilisateurs')
@section('content')
    @livewire('admin.gestion-utilisateurs')
@endsection
