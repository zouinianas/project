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
        /* ============================================================================= */
        /* DESIGN SYSTEM - VARIABLES MODERNES */
        /* ============================================================================= */
        :root {
            --primary-color: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --primary-light: #eef2ff;
            --primary-dark: #3730a3;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-dark: #1f2937;
            --text-grey: #6b7280;
            --text-light: #9ca3af;
            --bg-light: #f9fafb;
            --bg-lighter: #f3f4f6;
            --border-color: #e5e7eb;
            --border-light: #f3f4f6;
            --header-height: 80px;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ============================================================================= */
        /* CORPS & RESET */
        /* ============================================================================= */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* ============================================================================= */
        /* CORRECTION POUR STICKY ELEMENTS */
        /* ============================================================================= */
        .main-container,
        .pd-ltr-20,
        .card-box {
            overflow: visible !important;
        }

        /* ============================================================================= */
        /* HEADER MODERNE & STICKY */
        /* ============================================================================= */
        .header {
            height: var(--header-height);
            background: white;
            display: flex;
            align-items: center;
            padding: 0;
            box-shadow: var(--shadow-md);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
        }

        /* ============================================================================= */
        /* ZONE GAUCHE - LOGO/PLANNING (Largeur Fixe) */
        /* ============================================================================= */
        .header-left {
            width: 240px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid var(--border-color);
            background: linear-gradient(135deg, #ffffff 0%, var(--bg-light) 100%);
            flex-shrink: 0;
        }

        .home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.12);
            border: 1px solid transparent;
            gap: 8px;
        }

        .home-btn:hover {
            background: var(--primary-gradient);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
            text-decoration: none;
        }

        .home-btn:active {
            transform: translateY(0);
        }

        .home-btn.active-home {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
        }

        .home-btn i {
            margin-right: 8px;
            font-size: 16px;
        }

        /* ============================================================================= */
        /* ZONE CENTRALE - NAVIGATION (Élastique) */
        /* ============================================================================= */
        .header-center {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: space-around;
            height: 100%;
            padding: 0 20px;
            gap: 8px;
        }

        .quick-nav-btn {
            flex: 1;
            max-width: 160px;
            height: 65%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: var(--text-grey);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .quick-nav-btn i {
            font-size: 22px;
            margin-bottom: 6px;
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        /* HOVER STATE */
        .quick-nav-btn:hover {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(59, 130, 246, 0.08) 100%);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .quick-nav-btn:hover i {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        /* ACTIVE STATE */
        .quick-nav-btn.active {
            background: linear-gradient(135deg, #eef2ff 0%, #f0f9ff 100%);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .quick-nav-btn.active i {
            color: var(--primary-color);
        }

        .quick-nav-btn.active::before {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px 3px 0 0;
        }

        /* ============================================================================= */
        /* ZONE DROITE - PROFIL (Largeur Fixe) */
        /* ============================================================================= */
        .header-right {
            width: 280px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 30px;
            border-left: 1px solid var(--border-color);
            flex-shrink: 0;
            gap: 15px;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 40px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            background: transparent;
            gap: 12px;
        }

        .user-profile-btn:hover {
            background-color: var(--bg-light);
            border-color: var(--border-color);
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
        }

        .user-profile-btn:hover .user-avatar {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .user-info {
            text-align: left;
            line-height: 1.3;
        }

        .user-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--text-dark);
            display: block;
        }

        .user-role {
            font-size: 11px;
            color: var(--text-grey);
            display: block;
        }

        /* ============================================================================= */
        /* DROPDOWN MENU - PROFIL */
        /* ============================================================================= */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-top: 15px !important;
            padding: 8px 0;
            animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 10px 20px;
            color: var(--text-grey);
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 0 8px;
        }

        .dropdown-item:hover {
            background-color: var(--bg-light);
            color: var(--primary-color);
        }

        .dropdown-item.text-danger {
            color: var(--danger-color);
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        /* ============================================================================= */
        /* CONTENU PRINCIPAL */
        /* ============================================================================= */
        .main-container {
            padding-top: calc(var(--header-height) + 30px) !important;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 50px;
            min-height: 100vh;
        }

        .pd-ltr-20 {
            padding: 0 !important;
        }

        /* Masquer la sidebar */
        .left-side-bar,
        .mobile-menu-overlay {
            display: none !important;
        }

        /* ============================================================================= */
        /* CARTES & CONTENEURS */
        /* ============================================================================= */
        .card-box {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            background: white;
            transition: all 0.3s ease;
        }

        .card-box:hover {
            box-shadow: var(--shadow-md);
        }

        /* ============================================================================= */
        /* RESPONSIVE DESIGN */
        /* ============================================================================= */
        @media (max-width: 1200px) {
            .header-center {
                gap: 4px;
            }

            .quick-nav-btn {
                max-width: 140px;
                font-size: 11px;
            }

            .quick-nav-btn i {
                font-size: 18px;
                margin-bottom: 4px;
            }
        }

        @media (max-width: 992px) {
            /* Sur tablette, on cache la nav centrale */
            .header-center {
                display: none;
            }

            .header-left {
                width: auto;
                border: none;
                padding-left: 15px;
                background: white;
            }

            .header-right {
                width: auto;
                border: none;
                padding-right: 15px;
            }

            .header {
                justify-content: space-between;
                padding: 0 15px;
            }

            .main-container {
                padding-left: 15px;
                padding-right: 15px;
                padding-top: calc(var(--header-height) + 20px);
            }
        }

        @media (max-width: 768px) {
            .header {
                height: 70px;
                padding: 0 10px;
            }

            .header-left {
                width: auto;
                padding-left: 10px;
            }

            .header-right {
                width: auto;
                padding-right: 10px;
            }

            .home-btn {
                padding: 8px 16px;
                font-size: 12px;
            }

            .home-btn i {
                margin-right: 6px;
                font-size: 14px;
            }

            .user-info {
                display: none;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
            }

            .main-container {
                padding-left: 10px;
                padding-right: 10px;
                padding-top: calc(70px + 20px);
            }

            /* Badges utilisateur responsive */
            .user-profile-btn {
                padding: 4px 8px;
            }
        }

        @media (max-width: 480px) {
            :root {
                --header-height: 60px;
            }

            .header {
                height: 60px;
            }

            .home-btn {
                padding: 6px 12px;
                font-size: 11px;
            }

            .main-container {
                padding-top: calc(60px + 15px);
                padding-left: 10px;
                padding-right: 10px;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
            }
        }

        /* ============================================================================= */
        /* UTILITAIRES */
        /* ============================================================================= */
        .d-none {
            display: none !important;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        /* ============================================================================= */
        /* ANIMATIONS SMOOTHES */
        /* ============================================================================= */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* ============================================================================= */
        /* SCROLLBAR PERSONNALISÉE (Optionnel) */
        /* ============================================================================= */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-light);
        }
    </style>

    @stack('head-scripts')
</head>

<body class="header-white">

    {{-- ======================================================================== --}}
    {{-- HEADER FIXE MODERNE --}}
    {{-- ======================================================================== --}}
    <div class="header">

        {{-- 1. ZONE GAUCHE - LOGO/PLANNING --}}
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="home-btn {{ request()->routeIs('admin.dashboard') ? 'active-home' : '' }}" title="Aller au Planning">
                <i class="fa fa-calendar-check"></i>
                <span>Planning</span>
            </a>
        </div>

        {{-- 2. ZONE CENTRALE - NAVIGATION --}}
        <div class="header-center">



        </div>

        {{-- 3. ZONE DROITE - PROFIL UTILISATEUR --}}
        <div class="header-right">
            <div class="dropdown">
                <div class="user-profile-btn dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Menu utilisateur">
                    <img src="{{ Auth::user()->picture }}" class="user-avatar" alt="Avatar utilisateur">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Administrateur</span>
                    </div>
                </div>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       title="Se déconnecter">
                        <i class="dw dw-logout mr-2"></i> Se déconnecter
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- ======================================================================== --}}
    {{-- CONTENU PRINCIPAL --}}
    {{-- ======================================================================== --}}
    <div class="main-container">
        <div class="pd-ltr-20">
            @yield('content')
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- SCRIPTS --}}
    {{-- ======================================================================== --}}
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
        /**
         * FORCER LE LAYOUT PLEINE LARGEUR
         * Cette fonction corrige les marges du conteneur principal
         */
        function forceFullWidth() {
            if (typeof $ !== 'undefined') {
                $('.main-container').css('margin-left', '0px');
                $('.header').css('left', '0px').css('width', '100%');
            }
        }

        /**
         * INITIALISATION AU CHARGEMENT
         */
        $(document).ready(function() {
            forceFullWidth();

            // Observer les mutations pour corriger les styles si changements
            const observer = new MutationObserver(forceFullWidth);
            const target = document.querySelector('.main-container');
            if (target) {
                observer.observe(target, { attributes: true, attributeFilter: ['style'] });
            }

            // Configuration Toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-bottom-right',
                timeOut: 3000,
                extendedTimeOut: 1000,
                showEasing: 'swing',
                hideEasing: 'linear',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };
        });

        /**
         * GESTIONNAIRE GLOBAL TOASTR
         * Affiche les notifications de succès/erreur
         */
        window.addEventListener('showToastr', function(event) {
            // Fermer SweetAlert s'il est ouvert
            if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                Swal.close();
            }

            const detail = event.detail;
            let type = 'info';
            let message = '';

            // Parser les données du détail
            if (Array.isArray(detail) && detail.length > 0) {
                type = detail[0].type || detail[0];
                message = detail[0].message || detail[1];
            } else if (typeof detail === 'object') {
                type = detail.type;
                message = detail.message;
            }

            // Afficher la notification
            if (message && typeof toastr !== 'undefined') {
                toastr[type](message);
            }
        });

        /**
         * CONFIGURATION AJAX GLOBAL
         * Ajoute le token CSRF à toutes les requêtes
         */
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        /**
         * NAVIGATION ACTIVE - Mise à jour dynamique
         * Met en surbrillance le lien actif selon la page
         */
        $(document).ready(function() {
            const currentRoute = '{{ request()->route()->getName() }}';
            $('.quick-nav-btn').each(function() {
                $(this).removeClass('active');
            });

            // Ajouter la classe active selon la route

        });
    </script>

    @stack('scripts')

</body>

</html>
