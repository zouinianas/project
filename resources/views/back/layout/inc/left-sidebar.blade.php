<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}">
            {{-- Mettez votre logo ici ou du texte --}}
            <h3 class="text-white pl-2">BUREAU ORDRE</h3>
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

    <li>
        <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.dashboard') ? 'active' : '' }}">
            <span class="micon dw dw-house-1"></span><span class="mtext">Tableau de bord</span>
        </a>
    </li>

    <li>
        <a href="{{ route('admin.bordereaux') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.bordereaux') ? 'active' : '' }}">
            <span class="micon dw dw-email"></span><span class="mtext">Bureau d'Ordre</span>
        </a>
    </li>

    <li>
        <div class="dropdown-divider"></div>
    </li>
    <li>
        <div class="sidebar-small-cap">Administration</div>
    </li>
    <li>
        <a href="{{ route('admin.utilisateurs') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.utilisateurs') ? 'active' : '' }}">
            <span class="micon dw dw-user-13"></span><span class="mtext">Utilisateurs</span>
        </a>
    </li>

    </ul>
        </div>
    </div>
</div>
