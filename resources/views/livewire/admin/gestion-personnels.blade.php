<div>
    {{-- En-tête de la page --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-3">
            <div class="pull-left">
                <h4 class="h4 text-blue">Gérer le Personnel</h4>
                <p class="mb-0">Sélectionnez un département pour voir ses enseignants.</p>
            </div>
            <div class="pull-right">
                <button wire:click="openModalCreate()" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fa fa-plus"></i> Ajouter une personne
                </button>
            </div>
        </div>

        <hr>

        {{-- 1. BOUTONS DE FILTRE PAR DEPARTEMENT --}}
        <div class="mb-4">
            <label class="font-weight-bold text-secondary mb-2">Filtrer par Département :</label>
            <div class="d-flex flex-wrap" style="gap: 8px;">
                {{-- Bouton "Tous" --}}
                <button wire:click="filterByDepartement(null)"
                        class="btn btn-sm rounded-pill {{ is_null($currentDepartementId) ? 'btn-dark' : 'btn-outline-dark' }}">
                    Tous
                </button>

                {{-- Boutons Départements --}}
                @foreach($departements as $dept)
                    <button wire:click="filterByDepartement({{ $dept->id }})"
                            class="btn btn-sm rounded-pill {{ $currentDepartementId == $dept->id ? 'text-white shadow' : 'btn-outline-secondary' }}"
                            style="{{ $currentDepartementId == $dept->id ? 'background-color: '.$dept->couleur.'; border-color: '.$dept->couleur.';' : '' }}">
                        {{ $dept->nom }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 2. BARRE DE RECHERCHE --}}
        <div class="row mb-3">
            <div class="col-md-4">
                 <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-light"><i class="fa fa-search text-muted"></i></span>
                    </div>
                    <input type="text" class="form-control border-0 bg-light" placeholder="Rechercher un nom..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
            <div class="col-md-8 text-right text-muted small pt-2">
                Affichage de {{ $personnels->count() }} enseignant(s)
            </div>
        </div>

        {{-- 3. TABLEAU DES RESULTATS --}}
        <div class="table-responsive bg-white shadow-sm rounded">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50%;" class="pl-4">Nom complet (A-Z)</th>
                        <th style="width: 30%;">Département</th>
                        <th style="width: 20%;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($personnels as $person)
                        <tr wire:key="{{ $person->id }}">
                            {{-- Nom --}}
                            <td class="align-middle pl-4">
                                <span class="font-weight-bold text-dark" style="font-size: 1.1em;">
                                    {{ $person->nom }}
                                </span>
                            </td>

                            {{-- Département --}}
                            <td class="align-middle">
                                @if($person->departement)
                                    <span class="badge badge-pill text-white" style="background-color: {{ $person->departement->couleur ?? '#6c757d' }}; font-size: 0.85em; padding: 5px 10px;">
                                        {{ $person->departement->nom }}
                                    </span>
                                @else
                                    <span class="text-muted small font-italic">Non assigné</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <button wire:click="openModalEdit({{ $person->id }})" class="btn btn-sm btn-light text-primary mr-1" title="Modifier">
                                        <i class="dw dw-edit2"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $person->id }})" class="btn btn-sm btn-light text-danger" title="Supprimer">
                                        <i class="dw dw-delete-3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa fa-users fa-3x mb-3 text-light-gray"></i><br>
                                    Aucun enseignant trouvé dans ce département.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $personnels->links() }}
        </div>
    </div>

    {{-- MODAL --}}
    <div wire:ignore.self class="modal fade" id="personnel_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow-lg" wire:submit.prevent="save">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modalLabel">
                        {{ $isUpdateMode ? 'Modifier Personnel' : 'Ajouter Personnel' }}
                    </h5>
                    <button type="button" class="close text-white" wire:click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <div class="p-3">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" wire:model="nom" placeholder="ex: LAKHAL HAMZA">
                            @error('nom') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Département d'appartenance <span class="text-danger">*</span></label>
                            <select class="form-control form-control-lg" wire:model="departement_id">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($departements as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                                @endforeach
                            </select>
                            @error('departement_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">{{ $isUpdateMode ? 'Mettre à jour' : 'Enregistrer' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    @push('scripts')
    <script>
        window.addEventListener('showPersonnelModal', event => { $('#personnel_modal').modal('show'); });
        window.addEventListener('hidePersonnelModal', event => { $('#personnel_modal').modal('hide'); });

        window.addEventListener('showToastr', event => {
            if(event.detail[0] && typeof toastr !== 'undefined'){
                toastr[event.detail[0].type](event.detail[0].message);
            }
        });

        window.addEventListener('showDeleteConfirmation', event => {
            let id = event.detail;
            if(Array.isArray(event.detail)) id = event.detail[0];

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Voulez-vous supprimer ce professeur ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id);
                }
            });
        });
    </script>
    @endpush
</div>
