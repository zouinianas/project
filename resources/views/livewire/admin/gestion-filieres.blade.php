<div>
    <div class="pd-20 card-box mb-30">

        {{-- ETAPE 1 : CHOIX DU DEPARTEMENT --}}
        @if(!$selectedDepartementId)
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="h4 text-blue">Gestion des Filières</h4>
                    <p class="mb-30">Veuillez sélectionner un département.</p>
                </div>
                <div class="pull-right">
                    <button wire:click="openModalCreate()" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Nouvelle Filière
                    </button>
                </div>
            </div>

            <div class="row">
                @foreach($departements as $dept)
                <div class="col-lg-4 col-md-6 mb-20">
                    <div class="card h-100 shadow-sm"
                         style="cursor: pointer; border-left: 5px solid {{ $dept->couleur ?? '#000' }}; transition: transform 0.2s;"
                         wire:click="selectDepartement({{ $dept->id }})"
                         onmouseover="this.style.transform='scale(1.03)'"
                         onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body text-center">
                            <h3 class="h5" style="color: {{ $dept->couleur ?? '#333' }}">
                                {{ $dept->nom }}
                            </h3>
                            <div class="text-muted mt-2"><i class="fa fa-university fa-2x"></i></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
        {{-- NAVIGATION INTERNE --}}

            <div class="clearfix mb-20">
                <div class="pull-left">
                    <button wire:click="resetNavigation" class="btn btn-secondary btn-sm mb-2">
                        <i class="fa fa-arrow-left"></i> Départements
                    </button>
                    <h4 class="h4 text-blue">
                        {{ $departements->find($selectedDepartementId)->nom ?? 'Département' }}
                    </h4>
                </div>
                <div class="pull-right">
                    <button wire:click="openModalCreate()" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Ajouter une filière ici
                    </button>
                </div>
            </div>

            {{-- ETAPE 2 : ONGLETS CYCLES (DEUG, Licence, Master) --}}
            <div class="tab">
                <ul class="nav nav-tabs customtab" role="tablist">
                    @foreach($niveaux as $niv)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedNiveau === $niv->value ? 'active font-weight-bold text-primary' : '' }}"
                               wire:click.prevent="selectNiveau('{{ $niv->value }}')"
                               href="#" role="tab" style="cursor: pointer;">
                                {{ $niv->value }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content mt-4">
                <div class="tab-pane fade show active" role="tabpanel">

                    {{-- ETAPE 3 : CHOIX DE LA SPECIALITE --}}
                    @if(count($availableGroups) > 0)
                        <div class="mb-4">
                            <h5 class="h5 text-secondary mb-3">Choisissez une spécialité :</h5>
                            <div class="d-flex flex-wrap">
                                @foreach($availableGroups as $group)
                                    <button type="button"
                                            class="btn mr-2 mb-2 {{ $selectedFiliereGroup === $group ? 'btn-primary' : 'btn-outline-primary' }}"
                                            wire:click="selectFiliereGroup('{{ $group }}')">
                                        {{ $group }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Aucune filière trouvée pour ce niveau. <br>
                            <small>Utilisez le bouton "Ajouter" pour créer la première.</small>
                        </div>
                    @endif

                    {{-- ETAPE 4 : CHOIX DU SEMESTRE --}}
                    @if($selectedFiliereGroup && count($semestresDisponibles) > 0)
                        <div class="mb-4 text-center border-top pt-3">
                            <div class="btn-group" role="group">
                                @foreach($semestresDisponibles as $sem)
                                    <button type="button"
                                            class="btn {{ $selectedSemestre === $sem->value ? 'btn-info' : 'btn-outline-secondary' }}"
                                            wire:click="selectSemestre('{{ $sem->value }}')">
                                        {{ $sem->value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- TABLEAU DES FILIERES --}}
                        <div class="table-responsive bg-white shadow-sm p-3">
                            <h5 class="mb-3 text-blue">
                                Filière : {{ $selectedFiliereGroup }} - {{ $selectedSemestre }}
                            </h5>
                            <table class="table table-striped table-sm">
                                <thead class="bg-light">
                                    <th>Nom de la Filière</th>
                                    <th>Semestre</th>
                                    <th class="text-right">Actions</th>
                                </thead>
                                <tbody>
                                    @forelse ($filieres as $filiere)
                                        <tr wire:key="fil-{{ $filiere->id }}">
                                            <td class="align-middle pl-3"><strong>{{ $filiere->nom }}</strong></td>
                                            <td class="align-middle">
                                                <span class="badge badge-light border">{{ $filiere->semestre->value ?? $selectedSemestre }}</span>
                                            </td>
                                            <td class="text-right">
                                                <div class="table-actions">
                                                    <button wire:click="openModalEdit({{ $filiere->id }})" class="text-primary mx-2" style="border:none; background:transparent;">
                                                        <i class="dw dw-edit2"></i>
                                                    </button>
                                                    <button wire:click="confirmDelete({{ $filiere->id }})" class="text-danger mx-2" style="border:none; background:transparent;">
                                                        <i class="dw dw-delete-3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                Aucune filière trouvée pour ce semestre précis.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center mt-2">
                                @if(is_object($filieres) && method_exists($filieres, 'links'))
                                    {{ $filieres->links('livewire::simple-bootstrap') }}
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif
    </div>

    {{-- MODAL --}}
    <div wire:ignore.self class="modal fade" id="filiere_modal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit.prevent="save">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $isUpdateMode ? 'Modifier' : 'Ajouter' }} une Filière</h4>
                    <button type="button" class="close" wire:click="closeModal()"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Département</label>
                        <select class="form-control" wire:model="departement_id">
                            <option value="">-- Choisir --</option>
                            @foreach($departements as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                            @endforeach
                        </select>
                        @error('departement_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Niveau</label>
                        <select class="form-control" wire:model="niveau">
                            <option value="">-- Choisir --</option>
                            @foreach($niveaux as $niv)
                                <option value="{{ $niv->value }}">{{ $niv->value }}</option>
                            @endforeach
                        </select>
                        @error('niveau') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nom complet (ex: SMI - S3)</label>
                        <input type="text" class="form-control" wire:model="nom">
                        @error('nom') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        window.addEventListener('showFiliereModal', event => { $('#filiere_modal').modal('show'); });
        window.addEventListener('hideFiliereModal', event => { $('#filiere_modal').modal('hide'); });
        window.addEventListener('showToastr', event => {
             if(typeof toastr !== 'undefined') toastr[event.detail[0].type](event.detail[0].message);
        });
        window.addEventListener('showDeleteConfirmation', event => {
            Swal.fire({
                title: 'Confirmer la suppression ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33',
                confirmButtonText: 'Oui', cancelButtonText: 'Annuler'
            }).then((res) => { if (res.isConfirmed) @this.call('delete', event.detail); });
        });
    </script>
    @endpush
</div>
