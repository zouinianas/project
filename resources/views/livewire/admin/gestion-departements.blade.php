<div>
    {{-- En-tête de la page --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="h4 text-blue">Gestion des Départements</h4>
                <p>Liste des départements</p>
            </div>
            <div class="pull-right">
                <button wire:click="openModalCreate()" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Ajouter un département
                </button>
            </div>
        </div>

        {{-- La Table --}}
        <div class="table-responsive mt-4">
            <table class="table table-borderless table-striped table-sm">
                <thead class="bg-secondary text-white">
                    <th>#</th>
                    <th>Nom du Département</th>
                    <th>Couleur</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @forelse ($departements as $dept)
                        <tr wire:key="{{ $dept->id }}">
                            <td>{{ $dept->id }}</td>
                            <td>{{ $dept->nom }}</td>
                            <td>
                                <span class="badge text-white" style="background-color: {{ $dept->couleur }}; padding: 5px 10px;">
                                    {{ $dept->couleur }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button wire:click="openModalEdit({{ $dept->id }})" class="text-primary mx-2" style="border:none; background:transparent; cursor:pointer;">
                                        <i class="dw dw-edit2"></i> Modifier
                                    </button>
                                    <button wire:click="confirmDelete({{ $dept->id }})" class="text-danger mx-2" style="border:none; background:transparent; cursor:pointer;">
                                        <i class="dw dw-delete-3"></i> Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">Aucun département trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-blok mt-1 text-center">
            {{ $departements->links('livewire::simple-bootstrap') }}
        </div>
    </div>

    {{-- MODAL --}}
    <div wire:ignore.self class="modal fade" id="departement_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" wire:submit.prevent="save">
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ $isUpdateMode ? 'Modifier le Département' : 'Ajouter un Département' }}
                    </h4>
                    <button type="button" class="close" wire:click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Champ Nom --}}
                    <div class="form-group">
                        <label><b>Nom du département</b></label>
                        <input type="text" class="form-control" wire:model="nom" placeholder="ex: Géologie">
                        @error('nom') <span class="text-danger ml-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Champ Couleur (Sélecteur Visuel) --}}
                    <div class="form-group">
                        <label><b>Couleur distinctive</b></label>

                        {{-- Input caché ou readonly pour voir le code sélectionné --}}
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background-color: {{ $couleur ?? '#ffffff' }}; width: 45px; border: 1px solid #ccc;"></span>
                            </div>
                            <input type="text" class="form-control" wire:model="couleur" placeholder="Sélectionnez ci-dessous" readonly>
                        </div>
                        @error('couleur') <span class="text-danger ml-1">{{ $message }}</span> @enderror

                        {{-- Grille des couleurs disponibles --}}
                        <label class="mt-2 text-muted small">Cliquez sur une couleur disponible (les couleurs utilisées sont masquées) :</label>
                        <div class="d-flex flex-wrap p-2" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 5px;">

                            {{-- Si aucune couleur disponible --}}
                            @if(count($this->couleursDisponibles) === 0)
                                <span class="text-muted small w-100 text-center">Toutes les couleurs sont prises !</span>
                            @endif

                            {{-- Boucle sur les couleurs --}}
                            @foreach($this->couleursDisponibles as $c)
                                <div wire:click="selectCouleur('{{ $c }}')"
                                     style="
                                        background-color: {{ $c }};
                                        width: 32px; height: 32px;
                                        margin: 4px;
                                        cursor: pointer;
                                        border-radius: 50%;
                                        box-shadow: 0 2px 3px rgba(0,0,0,0.2);
                                        border: {{ $couleur === $c ? '3px solid #333' : '2px solid #fff' }};
                                        transform: {{ $couleur === $c ? 'scale(1.1)' : 'scale(1)' }};
                                        transition: all 0.2s;
                                     "
                                     title="{{ $c }}">
                                     {{-- Petite coche si sélectionné --}}
                                     @if($couleur === $c)
                                        <div style="text-align: center; color: white; line-height: 28px; font-size: 12px;">
                                            <i class="fa fa-check"></i>
                                        </div>
                                     @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateMode ? 'Enregistrer' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS JS --}}
    @push('scripts')
    <script>
        window.addEventListener('showDepartementModal', event => {
            $('#departement_modal').modal('show');
        });

        window.addEventListener('hideDepartementModal', event => {
            $('#departement_modal').modal('hide');
        });

        window.addEventListener('showToastr', event => {
            if(event.detail[0] && typeof toastr !== 'undefined'){
                toastr[event.detail[0].type](event.detail[0].message);
            }
        });

        window.addEventListener('showDeleteConfirmation', event => {
            Swal.fire({
                title: 'Attention !',
                text: "Supprimer ce département peut affecter les données associées.",
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
