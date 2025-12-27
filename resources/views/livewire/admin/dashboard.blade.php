<div>
    @push('head-scripts')
    <style>
        /* --- Styles des Onglets (Tabs) --- */
        .nav-tabs-custom {
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;

            /* --- NOUVEAU : STICKY POUR LES ONGLETS --- */
            /* Cela permet aux onglets de rester visibles en haut lors du défilement */
            position: -webkit-sticky; /* Pour Safari */
            position: sticky;
            top: 80px; /* Ajustez selon la hauteur de votre barre de menu principale (Header) */
            z-index: 950; /* Au-dessus du tableau et de la barre de contrôle */
            background-color: rgba(240, 242, 245, 0.95); /* Fond gris clair semi-transparent */
            backdrop-filter: blur(5px); /* Effet de flou */
            padding-top: 15px;
            padding-left: 15px;
            padding-right: 15px;
            /* Marges négatives pour toucher les bords du conteneur parent */
            margin-left: -15px;
            margin-right: -15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .nav-item-custom {
            padding: 12px 25px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px 8px 0 0;
            color: #6c757d;
            background: #e9ecef; /* Fond gris inactif */
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        /* Onglet Sorties Actif (Bleu) */
        .nav-item-custom.active-sorties {
            background-color: #4e73df;
            color: white;
            border-bottom: 2px solid #4e73df;
            box-shadow: 0 -4px 10px rgba(78, 115, 223, 0.2);
        }

        /* Onglet Missions Actif (Vert/Teal) */
        .nav-item-custom.active-missions {
            background-color: #1cc88a;
            color: white;
            border-bottom: 2px solid #1cc88a;
            box-shadow: 0 -4px 10px rgba(28, 200, 138, 0.2);
        }

        /* --- Styles des Cartes --- */
        .card-modern {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            background: white;
            /* overflow: visible; IMPORTANT POUR LE STICKY ET DROPDOWNS */
            margin-bottom: 30px;
        }

        .card-header-modern {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background: white;
        }

        /* --- Navigation Sticky (Collante & Centrée) --- */
        .sticky-controls {
            position: -webkit-sticky; /* Pour Safari */
            position: sticky;
            /* --- MODIFICATION IMPORTANTE --- */
            /* On descend cette barre à 145px pour qu'elle se colle SOUS les onglets sticky */
            /* 80px (Header) + ~65px (Hauteur des onglets) = 145px */
            top: 145px;
            z-index: 900;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-bottom: 2px solid #f1f1f1;
            padding-top: 10px;
            padding-bottom: 10px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            /* CENTRAGE DU CONTENU */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        /* --- Styles des Boutons --- */
        .btn-modern-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
        }

        .btn-modern-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(28, 200, 138, 0.3);
        }

        .btn-modern-dark {
            background: linear-gradient(135deg, #343a40 0%, #23272b 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(52, 58, 64, 0.3);
        }

        /* Petit bouton rond (+) dans le tableau */
        .btn-circle-add {
            width: 35px;
            height: 35px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: transform 0.2s;
        }

        .btn-circle-add:hover {
            transform: scale(1.1);
        }

        /* Boutons de navigation mois (< >) */
        .btn-nav-month {
            border-color: #ddd;
            color: #555;
            background: #fff;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .btn-nav-month:hover {
            background: #4e73df;
            color: #fff;
            border-color: #4e73df;
        }

        /* Style pour Toggle Input */
        .input-group-toggle .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Dropdown item cursor */
        .dropdown-item {
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* Retirer la flèche du bouton dropdown */
        .no-arrow::after {
            display: none;
        }
    </style>
    @endpush

    {{-- ======================================================================== --}}
    {{-- BARRE DE NAVIGATION (ONGLETS) - MAINTENANT STICKY                        --}}
    {{-- ======================================================================== --}}
    <div class="nav-tabs-custom">
        <div class="nav-item-custom {{ $activeTab === 'sorties' ? 'active-sorties' : '' }}"
             wire:click="switchTab('sorties')">
            <i class="fa fa-graduation-cap mr-2"></i> Sorties Etudiantes
        </div>
        <div class="nav-item-custom {{ $activeTab === 'missions' ? 'active-missions' : '' }}"
             wire:click="switchTab('missions')">
            <i class="fa fa-briefcase mr-2"></i> Ordres de Mission (Libres)
        </div>
    </div>


    {{-- ======================================================================== --}}
    {{-- ONGLET 1 : PLANNING DES SORTIES ETUDIANTES                               --}}
    {{-- ======================================================================== --}}
    @if($activeTab === 'sorties')
    <div class="card-modern">
        {{-- En-tête de la carte --}}
        <div class="card-header-modern">
            <div class="pull-left">
                <h4 style="color: #4e73df; font-weight: 700;">Planning des Sorties</h4>
                <p class="mb-0 text-muted small">Gestion des sorties pédagogiques et réservations.</p>
            </div>
            <div class="pull-right">
                {{-- BOUTON RESERVER --}}
                <button wire:click="openModalReservation()" class="btn btn-warning btn-sm ml-2 px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fa fa-clock-o"></i> Réserver une date
                </button>

                <button wire:click="openModalCreate()" class="btn btn-modern-primary btn-sm ml-2 px-3 py-2 rounded-pill">
                    <i class="fa fa-plus-circle"></i> Ajouter une sortie
                </button>
            </div>
        </div>

        <div id="tableView">

            {{-- 1. ZONE NON-STICKY : BOUTON ARCHIVE ANNUELLE (Reste en haut) --}}
            <div class="d-flex justify-content-end px-4 pt-3 pb-2">
                <div class="btn-group">
                    <button type="button"
                            class="btn btn-modern-dark btn-sm px-3 shadow-sm dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fa fa-file-archive-o mr-1"></i>
                        {{-- Logique corrigée : Saison Universitaire --}}
                        Télécharger Bilan Saison {{ $startSeasonYear }}-{{ $startSeasonYear+1 }}
                    </button>

                    <div class="dropdown-menu dropdown-menu-right shadow-sm">
                        <a class="dropdown-item text-success"
                           href="{{ route('admin.download_archive', ['year' => $startSeasonYear, 'format' => 'excel']) }}">
                            <i class="fa fa-file-excel-o mr-2"></i> Fichier Excel
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-primary"
                           href="{{ route('admin.download_archive', ['year' => $startSeasonYear, 'format' => 'word']) }}">
                            <i class="fa fa-file-word-o mr-2"></i> Version Word
                        </a>
                        <a class="dropdown-item text-danger"
                           href="{{ route('admin.download_archive', ['year' => $startSeasonYear, 'format' => 'pdf']) }}">
                            <i class="fa fa-file-pdf-o mr-2"></i> Version PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. ZONE STICKY : NAVIGATION CENTRÉE (MOIS/ANNEE) --}}
            <div class="sticky-controls">

                {{-- Bouton Précédent --}}
                <button class="btn btn-nav-month" wire:click="previousMonth" title="Mois précédent">
                    <i class="fa fa-chevron-left"></i>
                </button>

                <div class="d-flex align-items-center bg-white rounded px-2" style="border: 1px solid #ddd;">
                    <i class="fa fa-calendar text-primary mr-2"></i>

                    {{-- Select Mois --}}
                    <select wire:model.live="selectedMonth" class="form-control form-control-sm border-0 text-primary font-weight-bold" style="width: auto; box-shadow: none; cursor: pointer;">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ ucfirst(\Carbon\Carbon::create()->month($m)->locale('fr')->monthName) }}</option>
                        @endforeach
                    </select>

                    <span class="text-muted mx-1">/</span>

                    {{-- Select Année (Etendue -5 à +20 ans) --}}
                    <select wire:model.live="selectedYear" class="form-control form-control-sm border-0 text-primary font-weight-bold" style="width: auto; box-shadow: none; cursor: pointer;">
                        @foreach(range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year + 20) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Bouton Suivant --}}
                <button class="btn btn-nav-month" wire:click="nextMonth" title="Mois suivant">
                    <i class="fa fa-chevron-right"></i>
                </button>

            </div>

            {{-- Tableau Principal --}}
            <div class="pb-20 px-3 pt-3" style="overflow-x:auto;">
                <table class="table table-bordered table-sm table-hover" style="border-radius: 8px; overflow: hidden;">
                    <thead class="text-white" style="background-color: #4e73df;">
                        <tr>
                            <th scope="col" style="min-width: 150px;">Date et Jour</th>
                            <th scope="col" style="min-width: 100px;">Département</th>
                            <th scope="col" style="min-width: 150px;">Filière/Niveau</th>
                            <th scope="col" style="min-width: 150px;">Module / Objet</th>
                            <th scope="col" style="min-width: 200px;">Enseignants</th>
                            <th scope="col" style="min-width: 120px;">Destination</th>
                            <th scope="col" style="min-width: 120px;">Transport</th>
                            <th scope="col" style="min-width: 150px;">Ordonné à</th>
                            <th scope="col" style="min-width: 100px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jours_du_mois as $jour)
                            @php
                                $sortie = $jour['sortie'];
                                $dateStr = $jour['date_obj']->format('Y-m-d');
                                $isSunday = $jour['date_obj']->isSunday();

                                $bgColor = '#ffffff';
                                if ($isSunday) $bgColor = '#fff5f5';
                                elseif ($jour['is_weekend']) $bgColor = '#f8f9fa';

                                if ($sortie) {
                                    // 1. DÉPARTEMENT DIRECT
                                    if ($sortie->departement && $sortie->departement->couleur) {
                                        $bgColor = $sortie->departement->couleur . '30'; // Transparence
                                    }
                                    // 2. DÉPARTEMENT VIA MODULE
                                    elseif ($sortie->module && $sortie->module->filiere && $sortie->module->filiere->departement) {
                                        $bgColor = $sortie->module->filiere->departement->couleur . '30';
                                    }
                                    // 3. FALLBACK
                                    elseif ($sortie->statut === 'Réservé') {
                                        $bgColor = '#FFF3CD';
                                    }
                                }
                            @endphp

                            <tr wire:key="day-{{ $dateStr }}" style="background-color: {{ $bgColor }}; border-bottom: 1px solid #eee;">
                                {{-- 1. Date et Jour --}}
                                <td class="align-middle {{ $jour['is_weekend'] ? 'text-danger' : '' }} {{ $dateStr == $today ? 'bg-light-blue' : '' }}">
                                    <b>{{ $jour['date_obj']->format('d/m/Y') }}</b><br>
                                    <small class="text-uppercase font-weight-bold" style="font-size: 10px;">{{ $jour['date_obj']->locale('fr')->isoFormat('dddd') }}</small>

                                    @if($isSunday && !$sortie)
                                        <br><span class="badge badge-danger px-1">Bloqué</span>
                                    @endif
                                </td>

                                @if ($sortie)
                                    @php $isDebutJour = $sortie->date_debut->format('Y-m-d') === $dateStr; @endphp

                                    @if($sortie->statut === 'Réservé')
                                        {{-- LIGNE RÉSERVÉE --}}
                                        <td class="align-middle">
                                            @if($sortie->departement)
                                                <span class="badge text-white shadow-sm"
                                                      style="font-size: 0.9em; background-color: {{ $sortie->departement->couleur ?? '#ffc107' }}">
                                                    {{ $sortie->departement->nom }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Département Inconnu</span>
                                            @endif
                                        </td>
                                        <td colspan="5" class="align-middle text-center">
                                            <div style="background: rgba(255, 255, 255, 0.4); border: 2px dashed {{ $sortie->departement->couleur ?? '#ffc107' }}; border-radius: 5px; padding: 5px;">
                                                <i class="fa fa-clock-o mr-1" style="color: {{ $sortie->departement->couleur ?? '#ffc107' }}"></i>
                                                <strong style="color: {{ $sortie->departement->couleur ?? '#ffc107' }}" class="text-uppercase">Créneau Réservé</strong>
                                                @if($sortie->objet) <small class="text-muted d-block">"{{ $sortie->objet }}"</small> @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-uppercase small">{{ $sortie->chauffeur ?? 'N/A' }}</td>

                                        <td class="align-middle text-center">
                                            <button wire:click="openModalEdit({{ $sortie->id }})" class="btn btn-sm text-warning" title="Modifier la réservation">
                                                <i class="dw dw-edit2"></i>
                                            </button>
                                            <button wire:click="openModalConvert({{ $sortie->id }})" class="btn btn-sm text-success" title="Compléter les infos">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $sortie->id }})" class="btn btn-sm text-danger" title="Supprimer">
                                                <i class="dw dw-delete-3"></i>
                                            </button>
                                        </td>

                                    @else
                                        {{-- LIGNE STANDARD --}}
                                        <td class="align-middle">
                                            @if($sortie->departement)
                                                <span class="badge text-white shadow-sm" style="background-color: {{ $sortie->departement->couleur }}">
                                                    {{ $sortie->departement->nom }}
                                                </span>
                                            @elseif ($sortie->module && $sortie->module->filiere && $sortie->module->filiere->departement)
                                                <span class="badge text-white shadow-sm" style="background-color: {{ $sortie->module->filiere->departement->couleur }}">
                                                    {{ $sortie->module->filiere->departement->nom }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            @if ($sortie->module && $sortie->module->filiere)
                                                <b class="text-dark">{{ $sortie->module->filiere->niveau ? $sortie->module->filiere->niveau->value : '' }}</b>
                                                <br>
                                                <small>{{ $sortie->module->filiere->nom }}</small>
                                            @elseif($sortie->objet)
                                                 <span class="text-muted small">Hors-Cursus</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td class="align-middle text-primary font-weight-bold">
                                            @if($sortie->module)
                                                {{ $sortie->module->nom }}
                                            @elseif($sortie->objet)
                                                {{ $sortie->objet }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            @if($sortie->encadrants->count() > 0)
                                                <div class="d-flex flex-wrap">
                                                    @foreach($sortie->encadrants as $prof)
                                                        <span class="badge badge-light border m-1 text-dark">
                                                            {{ $prof->nom }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @elseif($sortie->personnel)
                                                 <span class="badge badge-light border text-dark">{{ $sortie->personnel }}</span>
                                            @else
                                                <span class="text-muted text-small">Aucun encadrant</span>
                                            @endif
                                        </td>

                                        <td class="align-middle">{{ $sortie->destination->nom ?? 'N/A' }}</td>
                                        <td class="align-middle">{{ $sortie->transport ? $sortie->transport->value : 'N/A' }}</td>
                                        <td class="align-middle text-uppercase small">{{ $sortie->chauffeur ?? 'N/A' }}</td>

                                        <td class="align-middle text-center">
                                            @if($isDebutJour)
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <button wire:click="openModalEdit({{ $sortie->id }})" class="btn btn-sm text-primary" title="Modifier"><i class="dw dw-edit2"></i></button>
                                                    <button wire:click="confirmDelete({{ $sortie->id }})" class="btn btn-sm text-danger" title="Supprimer"><i class="dw dw-delete-3"></i></button>

                                                    @if($sortie->date_validation)
                                                        <div class="btn-group dropdown">
                                                            <button type="button" class="btn btn-sm text-info dropdown-toggle no-arrow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Imprimer">
                                                                <i class="fa fa-print"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                                                <h6 class="dropdown-header">Imprimer (Déjà Validé)</h6>
                                                                <a class="dropdown-item text-primary" href="{{ route('admin.print_sortie', ['id' => $sortie->id, 'format' => 'word']) }}">
                                                                    <i class="fa fa-file-word-o mr-2"></i> Word
                                                                </a>
                                                                <a class="dropdown-item text-danger" href="{{ route('admin.print_sortie', ['id' => $sortie->id, 'format' => 'pdf']) }}">
                                                                    <i class="fa fa-file-pdf-o mr-2"></i> PDF
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <button wire:click="cancelValidation({{ $sortie->id }})" class="btn btn-sm text-secondary" title="Annuler la validation">
                                                            <i class="fa fa-times-circle"></i>
                                                        </button>
                                                    @else
                                                        <div class="btn-group dropdown">
                                                            <button type="button" class="btn btn-sm text-warning dropdown-toggle no-arrow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Valider & Imprimer">
                                                                <i class="fa fa-check-square-o"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                                                <h6 class="dropdown-header">Valider & Imprimer ?</h6>
                                                                <button class="dropdown-item text-primary" wire:click="validateAndPrint({{ $sortie->id }}, 'word')">
                                                                    <i class="fa fa-file-word-o mr-2"></i> Word
                                                                </button>
                                                                <button class="dropdown-item text-danger" wire:click="validateAndPrint({{ $sortie->id }}, 'pdf')">
                                                                    <i class="fa fa-file-pdf-o mr-2"></i> PDF
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($sortie->date_debut->format('Y-m-d') !== $sortie->date_fin->format('Y-m-d'))
                                                    <small class="d-block mt-1 text-info font-weight-bold"> <i class="fa fa-arrow-right"></i> {{ $sortie->date_fin->format('d/m') }}</small>
                                                @endif
                                            @else
                                                <small class="text-muted">En cours...</small>
                                            @endif
                                        </td>
                                    @endif
                                @else
                                    <td colspan="9" class="align-middle">
                                        @if(!$isSunday)
                                            <div class="d-flex justify-content-end pr-2">
                                                <button class="btn btn-circle-add btn-outline-primary" wire:click="openModalCreate('{{ $dateStr }}')" title="Ajouter une sortie">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center"><i class="fa fa-ban text-danger opacity-50"></i></div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">Aucune donnée trouvée pour ce mois.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif


    {{-- ======================================================================== --}}
    {{-- ONGLET 2 : AUTRES ORDRES DE MISSION (LIBRES)                             --}}
    {{-- ======================================================================== --}}
    @if($activeTab === 'missions')
    <div class="card-modern">
        <div class="card-header-modern">
            <div class="pull-left">
                <h4 style="color: #1cc88a; font-weight: 700;">Ordres de Mission (Libres)</h4>
                <p class="mb-0 text-muted small">Missions administratives, techniques ou hors cursus.</p>
            </div>
            <div class="pull-right">
                <button wire:click="openModalOrdreLibre()" class="btn btn-modern-success btn-sm ml-2 px-3 py-2 rounded-pill">
                    <i class="fa fa-file-text-o"></i> Créer Ordre Libre
                </button>
            </div>
        </div>

        {{-- IMPORTANT : On retire "table-responsive" ici pour laisser le dropdown déborder --}}
        <div class="pb-20 px-3 pt-3">
            <table class="table table-striped table-hover">
                <thead class="text-white" style="background-color: #1cc88a;">
                    <tr>
                        <th width="10%">N° Ordre</th>
                        <th width="20%">Période</th>
                        <th width="25%">Objet de la mission</th>
                        <th width="20%">Personnel Concerné</th>
                        <th width="10%">Transport</th>
                        <th width="10%">Ordonné à</th>
                        <th width="5%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordres_libres as $ordre)
                        <tr>
                            <td><span class="badge badge-success">N° {{ $ordre->numero_ordre }}</span></td>
                            <td>
                                <b>{{ $ordre->date_debut->format('d/m/Y') }}</b>
                                @if($ordre->date_debut != $ordre->date_fin && $ordre->date_fin)
                                    <i class="fa fa-arrow-right text-muted mx-1"></i> {{ $ordre->date_fin->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="font-weight-bold text-dark">{{ $ordre->objet ?? 'Autre' }}</td>
                            <td class="small">{{ $ordre->personnel ?? 'Non spécifié' }}</td>
                            <td>{{ $ordre->transport ? $ordre->transport->value : '-' }}</td>
                            <td class="text-uppercase small">{{ $ordre->chauffeur }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button wire:click="openModalEdit({{ $ordre->id }})" class="btn btn-sm text-success"><i class="dw dw-edit2"></i></button>
                                    <button wire:click="confirmDelete({{ $ordre->id }})" class="btn btn-sm text-danger"><i class="dw dw-delete-3"></i></button>

                                    @if($ordre->date_validation)
                                        <div class="btn-group dropdown">
                                            <button type="button" class="btn btn-sm text-info dropdown-toggle no-arrow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Imprimer">
                                                <i class="fa fa-print"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow-sm" style="z-index: 1050;">
                                                <h6 class="dropdown-header">Déjà Validé</h6>
                                                <a class="dropdown-item text-primary" href="{{ route('admin.print_ordre_libre', ['id' => $ordre->id, 'format' => 'word']) }}">
                                                    <i class="fa fa-file-word-o mr-2"></i> Word
                                                </a>
                                                <a class="dropdown-item text-danger" href="{{ route('admin.print_ordre_libre', ['id' => $ordre->id, 'format' => 'pdf']) }}">
                                                    <i class="fa fa-file-pdf-o mr-2"></i> PDF
                                                </a>
                                            </div>
                                        </div>
                                        <button wire:click="cancelValidation({{ $ordre->id }})" class="btn btn-sm text-secondary" title="Annuler la validation">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                    @else
                                        <div class="btn-group dropdown">
                                            <button type="button" class="btn btn-sm text-warning dropdown-toggle no-arrow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Valider & Imprimer">
                                                <i class="fa fa-check-square-o"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow-sm" style="z-index: 1050;">
                                                <h6 class="dropdown-header">Valider & Imprimer ?</h6>
                                                <button class="dropdown-item text-primary" wire:click="validateAndPrint({{ $ordre->id }}, 'word')">
                                                    <i class="fa fa-file-word-o mr-2"></i> Word
                                                </button>
                                                <button class="dropdown-item text-danger" wire:click="validateAndPrint({{ $ordre->id }}, 'pdf')">
                                                    <i class="fa fa-file-pdf-o mr-2"></i> PDF
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa fa-folder-open-o fa-3x mb-3 d-block text-success opacity-50"></i>
                                <em>Aucun ordre de mission libre enregistré pour ce mois.</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif


    {{-- ======================================================================== --}}
    {{-- MODAL 1 : SORTIE STANDARD (Style Bleu)                                   --}}
    {{-- ======================================================================== --}}
    <div wire:ignore.self class="modal fade" id="sortie_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content border-0 shadow-lg" wire:submit.prevent="saveSortie">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                    <h5 class="modal-title font-weight-bold">
                        {{ $isUpdateMode ? 'Modifier / Compléter la Sortie' : 'Nouvelle Sortie Étudiante' }}
                    </h5>
                    <button type="button" class="close text-white" wire:click="closeModal()">×</button>
                </div>
                <div class="modal-body bg-light">
                    {{-- Numéro d'ordre (AUTO) --}}
                    <div class="card p-3 border-0 shadow-sm mb-3">
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label font-weight-bold text-primary">Numéro Ordre</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control font-weight-bold text-center bg-light"
                                       wire:model="numero_ordre"
                                       readonly
                                       style="font-size: 1.2em; color: #4e73df;">
                            </div>
                        </div>
                    </div>

                    {{-- Détails Pédagogiques --}}
                    <div class="card p-3 border-0 shadow-sm mb-3">
                        <h6 class="text-primary border-bottom pb-2 mb-3">Détails Pédagogiques</h6>

                        {{-- 1. Département (Toujours visible) --}}
                        <div class="form-group">
                            <label>1. Département</label>
                            <select class="form-control" wire:model.live="departement_id">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($departements as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                                @endforeach
                            </select>
                            @error('departement_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Conditionnement de l'affichage --}}
                        @if(!$is_special_dept)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>2. Filière</label>
                                        <select class="form-control" wire:model.live="filiere_id">
                                            <option value="">-- D'abord le département --</option>
                                            @foreach($filieres as $filiere)
                                                <option value="{{ $filiere->id }}">{{ $filiere->niveau ? $filiere->niveau->value : '' }} - {{ $filiere->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>3. Module</label>
                                        <div class="input-group">
                                            @if($is_new_module)
                                                <input type="text" class="form-control border-primary"
                                                       wire:model="new_module_nom"
                                                       placeholder="Nom du nouveau module...">
                                                <div class="input-group-append">
                                                    <button class="btn btn-danger" type="button" wire:click="toggleNewModule"><i class="fa fa-times"></i></button>
                                                </div>
                                            @else
                                                <select class="form-control" wire:model="module_id">
                                                    <option value="">-- D'abord la filière --</option>
                                                    @foreach($modules as $module)
                                                        <option value="{{ $module->id }}">{{ $module->nom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-success" type="button" wire:click="toggleNewModule"><i class="fa fa-plus"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Champ Optionnel pour l'Objet & Personnel --}}
                            <div class="form-group mb-3">
                                <label>Objet de la mission</label>
                                <input type="text" class="form-control border-info" wire:model="objet" placeholder="Optionnel">
                            </div>
                            <div class="form-group mb-0">
                                <label>Personnel / Enseignants</label>
                                <input type="text" class="form-control border-info" wire:model="personnel" placeholder="Optionnel">
                            </div>
                        @endif

                        <div class="form-group">
                            <label>4. Destination</label>
                            <div class="input-group">
                                @if($is_new_destination)
                                    <input type="text" class="form-control border-primary"
                                           wire:model="new_destination_nom"
                                           placeholder="Nom de la nouvelle destination...">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger" type="button" wire:click="toggleNewDestination"><i class="fa fa-times"></i></button>
                                    </div>
                                @else
                                    <select class="form-control" wire:model="destination_id">
                                        <option value="">-- Sélectionnez --</option>
                                        @foreach($destinations as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->nom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" type="button" wire:click="toggleNewDestination"><i class="fa fa-plus"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Sélecteur d'encadrants --}}
                        @if(!$is_special_dept)
                            <div class="form-group">
                                <label>5. Encadrants (Accompagnateurs)</label>
                                <div class="input-group mb-2">
                                    @if($is_new_encadrant)
                                        <input type="text" class="form-control border-primary"
                                               wire:model="new_encadrant_nom"
                                               placeholder="Nom complet...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" wire:click.prevent="addEncadrant">Ajouter</button>
                                            <button class="btn btn-danger" type="button" wire:click="toggleNewEncadrant"><i class="fa fa-times"></i></button>
                                        </div>
                                    @else
                                        <select class="form-control" wire:model="encadrant_to_add">
                                            <option value="">-- Choisir un enseignant --</option>
                                            @foreach($personnels_list as $prof)
                                                <option value="{{ $prof->id }}">{{ $prof->nom }} ({{ $prof->departement->nom ?? '' }})</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" wire:click.prevent="addEncadrant"><i class="fa fa-plus"></i> Ajouter</button>
                                            <button class="btn btn-outline-success" type="button" wire:click="toggleNewEncadrant">Nouveau ?</button>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-2 border rounded bg-white">
                                    @foreach($selected_encadrants_objects as $prof_selected)
                                        <span class="badge badge-primary p-2 m-1 font-14">
                                            {{ $prof_selected->nom }}
                                            <span style="cursor: pointer; margin-left: 8px; color: #fff;" wire:click="removeEncadrant({{ $prof_selected->id }})">&times;</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Logistique --}}
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Date Début</label>
                                <input type="date" class="form-control" wire:model.live="date_debut" min="{{ $minDate }}">
                                @error('date_debut') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Date Fin</label>
                                <input type="date" class="form-control" wire:model="date_fin" min="{{ $minDate }}">
                                @error('date_fin') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Transport</label>
                                <select class="form-control" wire:model="transport">
                                    <option value="">-- --</option>
                                    @foreach($transports as $bus)
                                        <option value="{{ $bus->value }}">{{ $bus->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Ordonné à</label>
                                <input type="text" class="form-control" wire:model="chauffeur">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- MODAL 2 : ORDRE LIBRE (Style Vert/Teal)                                  --}}
    {{-- ======================================================================== --}}
    <div wire:ignore.self class="modal fade" id="ordre_libre_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content border-0 shadow-lg" wire:submit.prevent="saveSortie">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                    <h5 class="modal-title font-weight-bold"><i class="fa fa-briefcase"></i> Mission Libre</h5>
                    <button type="button" class="close text-white" wire:click="closeModal()">×</button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card p-3 border-0 shadow-sm mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark">Ordonné à</label>
                            <input type="text" class="form-control font-weight-bold text-success" wire:model="chauffeur" list="personnel_list_options" autocomplete="off">
                            <datalist id="personnel_list_options">
                                @foreach($personnels_list as $p)
                                    <option value="{{ $p->nom }}">{{ $p->departement->nom ?? 'Autre' }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="card p-3 border-0 shadow-sm mb-3">
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label font-weight-bold text-success">N° Ordre</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control font-weight-bold text-center" wire:model="numero_ordre" style="color: #1cc88a; font-size: 1.2em;">
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 border-0 shadow-sm mb-3">
                         <div class="form-group">
                            <label class="font-weight-bold">Objet</label>
                            <input type="text" class="form-control" wire:model="objet">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Personnel</label>
                            <textarea class="form-control" wire:model="personnel"></textarea>
                        </div>
                    </div>

                    <div class="card p-3 border-0 shadow-sm">
                        <div class="row">
                            <div class="col-md-6"><label>Début</label><input type="date" class="form-control" wire:model.live="date_debut"></div>
                            <div class="col-md-6"><label>Fin</label><input type="date" class="form-control" wire:model="date_fin"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Transport</label>
                                <select class="form-control" wire:model="transport">
                                    <option value="">-- Aucun --</option>
                                    @foreach($transports as $bus)
                                        <option value="{{ $bus->value }}">{{ $bus->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-success px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- MODAL 3 : RÉSERVATION (Nouveau)                                          --}}
    {{-- ======================================================================== --}}
    <div wire:ignore.self class="modal fade" id="reservation_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow-lg" wire:submit.prevent="saveSortie">
                <div class="modal-header text-white bg-warning">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-clock-o"></i> Réserver</h5>
                    <button type="button" class="close text-white" wire:click="closeModal()">×</button>
                </div>
                <div class="modal-body bg-light">
                    <div class="form-group row">
                        <label class="col-sm-4 font-weight-bold">N° Ordre</label>
                        <div class="col-sm-8"><input type="number" class="form-control text-center font-weight-bold" wire:model="numero_ordre"></div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Département</label>
                        <select class="form-control" wire:model="departement_id">
                            <option value="">-- Sélectionnez --</option>
                            @foreach($departements as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6"><label>Début</label><input type="date" class="form-control" wire:model.live="date_debut"></div>
                        <div class="col-6"><label>Fin</label><input type="date" class="form-control" wire:model="date_fin"></div>
                    </div>
                    <div class="form-group mt-3">
                        <label>Observation</label>
                        <input type="text" class="form-control" wire:model="objet">
                    </div>
                    <div class="form-group mt-2">
                        <label>Chauffeur</label>
                        <input type="text" class="form-control" wire:model="chauffeur">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-warning px-4 text-white font-weight-bold">Bloquer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- SCRIPTS JAVASCRIPT                                                       --}}
    {{-- ======================================================================== --}}
    @push('scripts')
    <script>
        window.addEventListener('showSortieModal', event => { $('#sortie_modal').modal('show'); });
        window.addEventListener('showOrdreLibreModal', event => { $('#ordre_libre_modal').modal('show'); });
        window.addEventListener('showReservationModal', event => { $('#reservation_modal').modal('show'); });

        window.addEventListener('hideSortieModal', event => { $('#sortie_modal').modal('hide'); });
        window.addEventListener('hideOrdreLibreModal', event => { $('#ordre_libre_modal').modal('hide'); });
        window.addEventListener('hideReservationModal', event => { $('#reservation_modal').modal('hide'); });

        window.addEventListener('showToastr', event => {
            if(event.detail[0].message === 'Enregistré avec succès.') {
                $('.modal').modal('hide');
            }
            if(event.detail[0] && typeof toastr !== 'undefined'){
                toastr[event.detail[0].type](event.detail[0].message);
            }
        });

        window.addEventListener('showDeleteConfirmation', event => {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if (result.isConfirmed) {
                    let idToDelete = event.detail.id ?? event.detail[0].id;
                    if(idToDelete) { @this.call('deleteSortie', idToDelete); }
                }
            });
        });

        window.addEventListener('startDownload', event => {
            setTimeout(() => { window.location.href = event.detail.url; }, 500);
        });
    </script>
    @endpush
</div>
