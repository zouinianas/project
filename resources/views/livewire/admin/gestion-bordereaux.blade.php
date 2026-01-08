<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Gestion des Bordereaux</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bordereaux</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button wire:click="openAddModal" class="btn btn-primary">
                    <i class="icon-copy dw dw-add"></i> Nouveau Bordereau
                </button>
            </div>
        </div>
    </div>

    <div class="card-box mb-30">
        <div class="pd-20">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="text-blue h4">Liste des envois</h4>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="Rechercher par numéro, destinataire ou objet..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>

        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">N° / Année</th>
                        <th>Date</th>
                        <th>Destinataire</th>
                        <th>Objet</th>
                        <th>Pièces</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courriers as $item)
                        <tr>
                            <td class="table-plus">
                                <span class="badge badge-pill badge-primary" style="font-size: 1.1em;">
                                    N° {{ $item->numero_ordre }} / {{ $item->annee }}
                                </span>
                            </td>
                            <td>{{ $item->date_depart ? $item->date_depart->format('d/m/Y') : '-' }}</td>
                            <td>{{ Str::limit($item->destinataire, 30) }}</td>
                            <td>{{ Str::limit($item->objet, 40) }}</td>
                            <td>{{ $item->nombre_pieces }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#" wire:click.prevent="edit({{ $item->id }})"><i class="dw dw-edit2"></i> Modifier</a>

                                        <a class="dropdown-item" href="{{ route('admin.bordereau.download', $item->id) }}">
                                            <i class="dw dw-file-word"></i> Télécharger Word
                                        </a>

                                        <button class="dropdown-item text-danger" wire:confirm="Voulez-vous vraiment supprimer ce bordereau ?" wire:click="delete({{ $item->id }})"><i class="dw dw-delete-3"></i> Supprimer</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="empty-state text-center">
                                    <img src="/back/vendors/images/product-img1.jpg" style="height: 100px; opacity:0.5" alt="">
                                    <p class="mt-2">Aucun bordereau trouvé.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-4 py-2">
                {{ $courriers->links() }}
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="bordereau-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isEditMode ? 'Modifier le Bordereau' : 'Nouveau Bordereau' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de départ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="date_depart">
                                    @error('date_depart') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre de pièces</label>
                                    <input type="number" class="form-control" wire:model="nombre_pieces" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Destinataire <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="destinataire" placeholder="Ex: M. le Président de l'Université...">
                            @error('destinataire') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Objet <span class="text-danger">*</span></label>
                            <textarea class="form-control" wire:model="objet" rows="3" placeholder="Objet du courrier..."></textarea>
                            @error('objet') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Observations</label>
                            <textarea class="form-control" wire:model="observation" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove>Enregistrer</span>
                            <span wire:loading>Traitement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Écouteur pour OUVRIR le modal
    window.addEventListener('showModal', event => {
        $('#bordereau-modal').modal('show');
    });

    // Écouteur pour FERMER le modal
    window.addEventListener('hideModal', event => {
        $('#bordereau-modal').modal('hide');
    });

    // Écouteur pour les notifications
    window.addEventListener('showToastr', event => {
        if(typeof toastr !== 'undefined'){
            toastr[event.detail[0].type](event.detail[0].message);
        } else {
            alert(event.detail[0].message);
        }
    });
</script>
@endpush
