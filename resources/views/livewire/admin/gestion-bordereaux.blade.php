<div>

    {{-- BARRE D'ACTIONS (Recherche + Bouton Nouveau) --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-blue h4">Registre des Départs</h4>
                <p class="mb-30">Gestion numérique des bordereaux d'envoi</p>
            </div>
            <div class="pull-right">
                <button wire:click="openModal" class="btn btn-primary btn-sm scroll-click" type="button">
                    <i class="fa fa-plus"></i> Nouveau Bordereau
                </button>
            </div>
        </div>

        {{-- Barre de Recherche --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Rechercher (Destinataire, Objet...)" wire:model.live.debounce.500ms="search">
            </div>
        </div>

        {{-- TABLEAU (Style Registre Papier) --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 10%;">N° Ordre</th>
                        <th style="width: 12%;">Date Départ</th>
                        <th style="width: 20%;">Destinataire</th>
                        <th style="width: 35%;">Objet (Analyse)</th>
                        <th style="width: 5%;">Pièces</th>
                        <th style="width: 18%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courriers as $c)
                        <tr>
                            <td class="font-weight-bold text-blue">
                                {{ $c->numero_ordre }} / {{ $c->annee }}
                            </td>
                            <td>{{ $c->date_depart->format('d/m/Y') }}</td>
                            <td>{{ $c->destinataire }}</td>
                            <td>{{ Str::limit($c->objet, 60) }}</td>
                            <td class="text-center">
                                @if($c->nombre_pieces > 0)
                                    <span class="badge badge-secondary">{{ $c->nombre_pieces }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{-- Bouton Télécharger Word --}}
                                <a href="{{ route('admin.bordereaux.download', $c->id) }}" class="btn btn-sm btn-success" title="Télécharger Word">
                                    <i class="fa fa-file-word-o"></i>
                                </a>

                                {{-- Bouton Modifier --}}
                                <button wire:click="edit({{ $c->id }})" class="btn btn-sm btn-info" title="Modifier">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                {{-- Bouton Supprimer --}}
                                <button wire:click="delete({{ $c->id }})"
                                        onclick="confirm('Êtes-vous sûr de vouloir supprimer ce bordereau ?') || event.stopImmediatePropagation()"
                                        class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Aucune donnée trouvée. Commencez par créer un nouveau bordereau.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-2">
            {{ $courriers->links() }}
        </div>
    </div>

    {{-- MODAL (Formulaire d'Ajout/Modification) --}}
    <div wire:ignore.self class="modal fade" id="bordereau_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isUpdateMode ? 'Modifier le Bordereau' : 'Nouveau Bordereau' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">

                        <div class="row">
                            {{-- Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de Départ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="date_depart">
                                    @error('date_depart') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Nombre de pièces --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre de Pièces</label>
                                    <input type="number" class="form-control" wire:model="nombre_pieces" min="0">
                                </div>
                            </div>
                        </div>

                        {{-- Destinataire --}}
                        <div class="form-group">
                            <label>Destinataire <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ex: Monsieur le Président..." wire:model="destinataire">
                            @error('destinataire') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Objet --}}
                        <div class="form-group">
                            <label>Objet (Analyse) <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="4" placeholder="Résumé de l'affaire..." wire:model="objet"></textarea>
                            @error('objet') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Observations --}}
                        <div class="form-group">
                            <label>Observations (Optionnel)</label>
                            <textarea class="form-control" rows="2" wire:model="observation"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isUpdateMode ? 'Mettre à jour' : 'Enregistrer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS LOCAUX (Pour gérer l'ouverture/fermeture du Modal) --}}
    @push('scripts')
    <script>
        // Ouvrir le modal quand Livewire le demande
        window.addEventListener('showBordereauModal', event => {
            $('#bordereau_modal').modal('show');
        });

        // Fermer le modal quand Livewire le demande
        window.addEventListener('hideBordereauModal', event => {
            $('#bordereau_modal').modal('hide');
        });

        // Afficher les notifications Toastr
        window.addEventListener('showToastr', event => {
            if(typeof toastr !== 'undefined'){
                toastr[event.detail[0].type](event.detail[0].message);
            }
        });
    </script>
    @endpush

</div>
