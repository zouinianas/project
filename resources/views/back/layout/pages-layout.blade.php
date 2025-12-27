<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- LOGO & FAVICON --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}"/>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

    {{-- FONTS & ICONS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css"/>
    <link rel="stylesheet" href="/extra-assets/ijabo/css/ijabo.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui.theme.css">
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @livewireStyles
    @kropifyStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @stack('stylesheets')

    <style>
        /* --- 1. DESIGN SYSTEM --- */
        :root {
            --primary-color: #4C51BF;
            --primary-light: #EEF2FF;
            --text-dark: #1F2937;
            --text-grey: #6B7280;
            --header-height: 80px;
            --bg-body: #F9FAFB; /* Fond très légèrement gris pour le contraste */
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); }

        /* --- CORRECTION POUR LE STICKY HEADER --- */
        /* Important : cela permet aux éléments sticky enfants de fonctionner par rapport à la fenêtre */
        .main-container, .pd-ltr-20, .card-box {
            overflow: visible !important;
        }

        /* --- 2. HEADER MODERNE (LAYOUT ELASTIQUE) --- */
        .header {
            height: var(--header-height);
            background: #ffffff;
            display: flex;
            align-items: center;
            padding: 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); /* Ombre plus douce */
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }

        /* ZONE GAUCHE (Logo / Planning) - Largeur Fixe */
        .header-left {
            width: 220px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #F3F4F6;
        }

        .home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 25px;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 5px rgba(76, 81, 191, 0.1);
        }
        .home-btn:hover {
            background-color: #4C51BF;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(76, 81, 191, 0.2);
            text-decoration: none;
        }
        .home-btn i { margin-right: 8px; font-size: 16px; }


        /* ZONE CENTRALE (Navigation Elastique) - Prend tout l'espace */
        .header-center {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: space-between; /* Distribue l'espace */
            height: 100%;
            padding: 0 15px;
        }

        .quick-nav-btn {
            flex-grow: 1; /* Le bouton s'étire */
            max-width: 160px; /* Mais pas à l'infini */
            height: 65%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--text-grey);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 4px;
            position: relative;
            background: transparent;
        }

        .quick-nav-btn i {
            font-size: 22px;
            margin-bottom: 5px;
            color: #9CA3AF;
            transition: color 0.2s;
        }

        /* Hover Effect */
        .quick-nav-btn:hover {
            background-color: #F3F4F6;
            color: var(--primary-color);
        }
        .quick-nav-btn:hover i { color: var(--primary-color); }

        /* Active State */
        .quick-nav-btn.active {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        .quick-nav-btn.active i { color: var(--primary-color); }


        /* ZONE DROITE (Profil) - Largeur Fixe */
        .header-right {
            width: 260px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 30px;
            border-left: 1px solid #F3F4F6;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 40px;
            transition: background 0.2s;
            border: 1px solid transparent;
        }
        .user-profile-btn:hover {
            background-color: #F9FAFB;
            border-color: #E5E7EB;
        }

        .user-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .user-info { margin-left: 12px; text-align: left; }
        .user-name { font-weight: 700; font-size: 14px; color: var(--text-dark); display: block; line-height: 1.2;}
        .user-role { font-size: 11px; color: var(--text-grey); display: block; }

        /* --- 3. CONTENU --- */
        .main-container {
            padding-top: calc(var(--header-height) + 30px) !important;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 40px;
        }
        .pd-ltr-20 { padding: 0 !important; }
        .left-side-bar, .mobile-menu-overlay { display: none !important; }

        /* Responsive Mobile */
        @media (max-width: 992px) {
            .header-center { display: none; }
            .header-left { width: auto; border: none; padding-left: 15px; }
            .header-right { width: auto; border: none; padding-right: 15px; }
            .header { justify-content: space-between; padding: 0; }
            .user-info { display: none; }
        }
    </style>
    @stack('head-scripts')
</head>
<body class="header-white">

    <div class="header">

        {{-- 1. GAUCHE --}}
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="home-btn {{ request()->routeIs('admin.dashboard') ? 'active-home' : '' }}">
                <i class="fa fa-calendar-check"></i> Planning
            </a>
        </div>

        {{-- 2. CENTRE (Navigation Large) --}}
        <div class="header-center">

            <a href="{{ route('admin.departements') }}" class="quick-nav-btn {{ request()->routeIs('admin.departements') ? 'active' : '' }}">
                <i class="fa fa-building-o"></i>
                <span>Départements</span>
            </a>

            <a href="{{ route('admin.filieres') }}" class="quick-nav-btn {{ request()->routeIs('admin.filieres') ? 'active' : '' }}">
                <i class="fa fa-graduation-cap"></i>
                <span>Filières</span>
            </a>

            <a href="{{ route('admin.modules') }}" class="quick-nav-btn {{ request()->routeIs('admin.modules') ? 'active' : '' }}">
                <i class="fa fa-book"></i>
                <span>Modules</span>
            </a>

            <a href="{{ route('admin.personnels') }}" class="quick-nav-btn {{ request()->routeIs('admin.personnels') ? 'active' : '' }}">
                <i class="fa fa-users"></i>
                <span>Personnel</span>
            </a>

            <a href="{{ route('admin.destinations') }}" class="quick-nav-btn {{ request()->routeIs('admin.destinations') ? 'active' : '' }}">
                <i class="fa fa-map-marker-alt"></i>
                <span>Destinations</span>
            </a>

        </div>

        {{-- 3. DROITE --}}
        <div class="header-right">
            <div class="dropdown">
                <div class="user-profile-btn dropdown-toggle" role="button" data-toggle="dropdown">
                    <img src="{{ Auth::user()->picture }}" class="user-avatar" alt="">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Administrateur</span>
                    </div>
                </div>
                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="margin-top: 15px; border-radius: 10px;">
                    <a class="dropdown-item text-danger py-2" href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="dw dw-logout mr-2"></i> Se déconnecter
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="pd-ltr-20">
            @yield('content')
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
    <script src="/extra-assets/ijabo/js/ijabo.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @kropifyScripts
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Script pour forcer le layout pleine largeur
        function forceFullWidth() {
            if (typeof $ !== 'undefined') {
                $('.main-container').css('margin-left', '0px');
                $('.header').css('left', '0px').css('width', '100%');
            }
        }
        $(document).ready(function() {
            forceFullWidth();
            const observer = new MutationObserver(forceFullWidth);
            const target = document.querySelector('.main-container');
            if(target) observer.observe(target, { attributes: true, attributeFilter: ['style'] });
        });

        // Gestionnaire Toastr global
        window.addEventListener('showToastr', function(event) {
            if( Swal.isVisible() ) Swal.close();
            const detail = event.detail;
            let type = 'info', message = '';
            if (Array.isArray(detail) && detail.length > 0) {
                type = detail[0].type || detail[0];
                message = detail[0].message || detail[1];
            } else if (typeof detail === 'object') {
                type = detail.type; message = detail.message;
            }
            if (message && typeof toastr !== 'undefined') {
                toastr.options = { "closeButton": true, "progressBar": true, "positionClass": "toast-bottom-right", "timeOut": "3000" };
                toastr[type](message);
            }
        });

        if (typeof $ !== 'undefined') {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        }
    </script>
    @stack('scripts')
</body>
</html>
