<div>
    {{-- En-tête de la page (Titre et bouton "Ajouter") --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="h4 text-blue">Gérer les Destinations</h4>
                <p>Liste des lieux de sortie (ex: Azrou, Fès, Imouzer...).</p>
            </div>
            <div class="pull-right">
                <button wire:click="openModalCreate()" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Ajouter une destination
                </button>
            </div>
        </div>

        {{-- La Table --}}
        <div class="table-responsive mt-4">
            <table class="table table-borderless table-striped table-sm">
                <thead class="bg-secondary text-white">
                    <th>Nom de la Destination</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @forelse ($destinations as $destination)
                        <tr wire:key="{{ $destination->id }}">
                            <td>{{ $destination->nom }}</td>
                            <td>
                                <div class="table-actions">
                                    <button wire:click="openModalEdit({{ $destination->id }})" class="text-primary mx-2" style="border:none; background:transparent; cursor:pointer;">
                                        <i class="dw dw-edit2"></i> Modifier
                                    </button>
                                    <button wire:click="confirmDelete({{ $destination->id }})" class="text-danger mx-2" style="border:none; background:transparent; cursor:pointer;">
                                        <i class="dw dw-delete-3"></i> Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">
                                <span class="text-danger">Aucune destination trouvée.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-blok mt-1 text-center">
            {{ $destinations->links('livewire::simple-bootstrap') }}
        </div>
    </div>

    {{-- MODAL (Création / Édition) --}}
    <div wire:ignore.self class="modal fade" id="destination_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit.prevent="save">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">
                        {{ $isUpdateMode ? 'Modifier la Destination' : 'Ajouter une Destination' }}
                    </h4>
                    <button type="button" class="close" wire:click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label><b>Nom de la destination</b></label>
                        <input type="text" class="form-control" wire:model="nom" placeholder="ex: Azrou">
                        @error('nom') <span class="text-danger ml-1">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateMode ? 'Enregistrer les modifications' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT pour piloter le modal --}}
    @push('scripts')
    <script>
        window.addEventListener('showDestinationModal', event => {
            $('#destination_modal').modal('show');
        });

        window.addEventListener('hideDestinationModal', event => {
            $('#destination_modal').modal('hide');
        });

        window.addEventListener('showToastr', event => {
            if(event.detail[0] && typeof toastr !== 'undefined'){
                toastr[event.detail[0].type](event.detail[0].message);
            }
        });

        // SCRIPT POUR LA SUPPRESSION
        window.addEventListener('showDeleteConfirmation', event => {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Supprimer cette destination.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', event.detail);
                }
            });
        });
    </script>
    @endpush
</div>
