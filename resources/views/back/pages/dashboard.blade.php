@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Tableau de bord')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Tableau de Bord</h4>
                    <p class="text-muted font-14">Bienvenue, {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix progress-box">

        <div class="col-lg-4 col-md-6 col-sm-12 mb-30">
            <div class="card-box pd-30 height-100-p">
                <div class="progress-box text-center">
                    <h5 class="text-blue padding-top-10 h5">Année {{ date('Y') }}</h5>
                    <span class="d-block font-30 weight-500">{{ $stats['total_annee'] }}</span>
                    <span class="font-14 text-muted">Bordereaux émis</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-30">
            <div class="card-box pd-30 height-100-p">
                <div class="progress-box text-center">
                    <h5 class="text-light-green padding-top-10 h5">Aujourd'hui</h5>
                    <span class="d-block font-30 weight-500">{{ $stats['total_aujourdhui'] }}</span>
                    <span class="font-14 text-muted">Départs ce jour</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-30">
            <div class="card-box pd-30 height-100-p">
                <div class="progress-box text-center">
                    <h5 class="text-light-orange padding-top-10 h5">Total Global</h5>
                    <span class="d-block font-30 weight-500">{{ $stats['total_global'] }}</span>
                    <span class="font-14 text-muted">Base de données complète</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">Derniers Envois (Top 5)</h4>
        </div>
        <div class="pb-20">
            <table class="table table-striped nowrap">
                <thead>
                    <tr>
                        <th class="table-plus">N° / Année</th>
                        <th>Destinataire</th>
                        <th>Objet</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recents as $item)
                        <tr>
                            <td class="table-plus font-weight-bold text-primary">
                                #{{ $item->numero_ordre }}/{{ $item->annee }}
                            </td>
                            <td>{{ Str::limit($item->destinataire, 25) }}</td>
                            <td>{{ Str::limit($item->objet, 35) }}</td>
                            <td>{{ $item->date_depart ? $item->date_depart->format('d/m/Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.bordereau.download', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Télécharger Word">
                                    <i class="dw dw-file-word"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Aucun courrier récent.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
