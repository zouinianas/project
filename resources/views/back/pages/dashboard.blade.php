@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Tableau de Bord')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Accueil</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Tableau de Bord</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">Bureau d'Ordre</div>
                        <div class="font-14 text-secondary weight-500">Gestion des Départs</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#00eccf" style="color: rgb(0, 236, 207);">
                            <i class="icon-copy fa fa-paper-plane"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.bordereaux') }}" class="btn btn-primary btn-sm btn-block">
                        Accéder au Registre
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
