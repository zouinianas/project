<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>@yield('pageTitle') - Sorties FSDM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    {{-- FAVICON --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}"/>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}"/>

    {{-- FONTS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    {{-- CSS --}}
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            --primary-light: #dbeafe;
            --primary-dark: #1e3a8a;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --text-dark: #1f2937;
            --text-grey: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Animation de fond */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes backgroundMove {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }

        .auth-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            flex-direction: row;
            min-height: auto;
        }

        /* PARTIE GAUCHE - BRANDING AVEC LOGO */
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
            min-height: 650px;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        /* Logo FSDM */
        .auth-logo {
            width: 180px;
            height: 180px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 35px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            padding: 20px;
            border: 5px solid rgba(255, 255, 255, 0.2);
        }

        .auth-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .auth-branding {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .auth-branding h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            letter-spacing: -1px;
        }

        .auth-branding .description {
            font-size: 18px;
            opacity: 0.95;
            line-height: 1.6;
            max-width: 420px;
            margin: 0 auto 15px auto;
            font-weight: 500;
        }

        .auth-branding .subtitle {
            font-size: 16px;
            opacity: 0.85;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            display: inline-block;
        }

        /* Icône décorative */
        .decoration-icon {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.2);
            font-size: 80px;
            z-index: 0;
        }

        /* PARTIE DROITE - FORMULAIRE */
        .auth-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
            min-height: auto;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .auth-header p {
            color: var(--text-grey);
            font-size: 15px;
        }

        /* FORMULAIRES */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-grey);
            font-size: 18px;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 13px;
            margin-top: 6px;
            display: block;
            font-weight: 500;
        }

        /* BOUTONS */
        .btn-primary-modern {
            width: 100%;
            padding: 14px 24px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .btn-outline-modern {
            width: 100%;
            padding: 14px 24px;
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-outline-modern:hover {
            background: var(--primary-light);
            text-decoration: none;
        }

        /* CHECKBOX */
        .custom-checkbox-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .custom-checkbox-modern input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .custom-checkbox-modern label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
        }

        /* LIENS */
        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .auth-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .auth-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            color: var(--text-grey);
            font-size: 14px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: var(--text-grey);
            font-size: 14px;
            font-weight: 600;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider span {
            padding: 0 15px;
        }

        /* ALERTS */
        .alert-modern {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .alert-modern i {
            font-size: 18px;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .auth-card {
                flex-direction: column;
            }

            .auth-left {
                padding: 40px 30px;
                min-height: 300px;
            }

            .auth-right {
                padding: 40px 30px;
            }

            .auth-branding h1 {
                font-size: 40px;
            }

            .auth-logo {
                width: 150px;
                height: 150px;
            }

            .decoration-icon {
                font-size: 60px;
                bottom: 30px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .auth-left {
                padding: 30px 20px;
                min-height: 250px;
            }

            .auth-right {
                padding: 30px 20px;
            }

            .auth-branding h1 {
                font-size: 32px;
            }

            .auth-branding .description {
                font-size: 16px;
            }

            .auth-header h2 {
                font-size: 24px;
            }

            .auth-logo {
                width: 120px;
                height: 120px;
            }

            .input-wrapper input {
                padding: 12px 14px 12px 44px;
            }

            .decoration-icon {
                display: none;
            }
        }
    </style>

    @stack('stylesheets')
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            {{-- PARTIE GAUCHE - BRANDING AVEC LOGO FSDM --}}
            <div class="auth-left">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo-fsdm-fes.png') }}" alt="Logo FSDM Fès">
                </div>
                <div class="auth-branding">
                    <h1>Sorties</h1>
                    <p class="description">Système de Gestion des Sorties Professionnelles</p>
                    <span class="subtitle">FS Dhar Lmhraz - Fès</span>
                </div>
                <i class="fas fa-plane-departure decoration-icon"></i>
            </div>

            {{-- PARTIE DROITE - FORMULAIRE --}}
            <div class="auth-right">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>

    @stack('scripts')
</body>
</html>
