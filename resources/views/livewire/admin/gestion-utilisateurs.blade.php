<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Gestion des Utilisateurs</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button wire:click="openAddModal" class="btn btn-primary">
                    <i class="icon-copy dw dw-add-user"></i> Ajouter Utilisateur
                </button>
            </div>
        </div>
    </div>

    <div class="card-box mb-30">
        <div class="pd-20">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="text-blue h4">Liste des comptes</h4>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="Rechercher un nom ou email..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>

        <div class="pb-20">
            <table class="table table-striped hover nowrap">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="avatar mr-2 flex-shrink-0">
                                    <img src="{{ $user->picture ? asset('images/users/'.$user->picture) : asset('images/users/default-avatar.png') }}" class="border-radius-100 shadow" width="40" height="40" alt="">
                                </div>
                            </td>
                            <td class="font-weight-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#" wire:click.prevent="edit({{ $user->id }})"><i class="dw dw-edit2"></i> Modifier</a>
                                        <button class="dropdown-item text-danger" wire:confirm="Supprimer cet utilisateur ?" wire:click="delete({{ $user->id }})"><i class="dw dw-delete-3"></i> Supprimer</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $isEditMode ? 'Modifier' : 'Ajouter' }} un utilisateur</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" wire:model="name" placeholder="Ex: Ahmed Benani">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" wire:model="email" placeholder="email@usmba.ac.ma">
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Mot de passe {{ $isEditMode ? '(Laisser vide pour ne pas changer)' : '' }}</label>
                            <input type="password" class="form-control" wire:model="password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('showModal', event => { $('#user-modal').modal('show'); });
    window.addEventListener('hideModal', event => { $('#user-modal').modal('hide'); });
    window.addEventListener('showToastr', event => {
        if(typeof toastr !== 'undefined'){ toastr[event.detail[0].type](event.detail[0].message); }
    });
</script>
@endpush
