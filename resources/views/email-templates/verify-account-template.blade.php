<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            padding: 20px 0;
            background-color: #0073e6;
            color: #ffffff;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .email-body p {
            margin-bottom: 20px;
        }
        .reset-button {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            padding: 15px;
            text-align: center;
            background-color: #0073e6; /* Bleu */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            color: #999999;
        }
        @media (max-width: 600px) {
            .email-container {
                padding: 10px;
            }
            .reset-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Verifiez votre compte</h1>
        </div>
        <div class="email-body">
            <h1>Bonjour, {{ $user->name }}</h1>
            <p>Merci de vous etre inscrit. Veuillez cliquer sur le bouton ci-dessous pour verifier votre adresse e-mail et activer votre compte.</p>
            <a href="{{ $actionlink }}" target="_blank" class="reset-button">Verifier mon compte</a>
            <p>
                Si vous n'avez pas cree de compte, veuillez ignorer cet e-mail.
            </p>
            <p>Merci,<br>L'equipe Sorties-2025-2026</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Sorties-2025-2026. Tous droits reserves.</p>
        </div>
    </div>
</body>
</html>
