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

                {{-- Lien Accueil --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-house-1"></span><span class="mtext">Accueil</span>
                    </a>
                </li>

                {{-- Lien Bureau d'Ordre --}}
                <li>
                    <a href="{{ route('admin.bordereaux') }}" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-email"></span><span class="mtext">Départs (Registre)</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>

                {{-- Lien Déconnexion --}}
                <li>
                    <a href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-menu').submit();"
                       class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-logout"></span><span class="mtext">Se déconnecter</span>
                    </a>
                    <form id="logout-form-menu" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>
