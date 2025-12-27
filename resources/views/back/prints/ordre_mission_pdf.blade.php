<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ordre de Mission</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* OBLIGATOIRE pour l'Arabe */
            font-size: 12px;
            color: #000;
            margin: 1cm; /* Marge standard Word */
        }

        /* TABLEAU HEADER (En-tête) */
        .header-table {
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-fr {
            width: 50%;
            text-align: left;
            vertical-align: top;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-ar {
            width: 50%;
            text-align: right;
            vertical-align: top;
            font-size: 12px;
            font-weight: bold;
            direction: rtl;
        }

        /* TITRE */
        .titre {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .titre-ar {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* NUMERO */
        .numero {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* TABLEAU CONTENU (C'est ça qui va aligner comme Word) */
        .content-table {
            width: 100%;
            border-collapse: collapse; /* Enlève les espaces entre les cellules */
        }
        .content-table td {
            padding-bottom: 15px; /* Espace entre chaque ligne (comme "Interligne" dans Word) */
            vertical-align: top;
        }

        /* Colonne de GAUCHE (Les titres) */
        .col-label {
            width: 160px; /* Largeur FIXE : ça ne bougera pas */
            font-weight: bold;
            white-space: nowrap;
        }

        /* Colonne de DROITE (Les données) */
        .col-value {
            font-weight: normal;
            text-align: left;
        }

        /* PIED DE PAGE */
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature {
            text-align: center;
            font-weight: bold;
        }

        /* ADRESSE EN BAS */
        .footer-address {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            text-align: center;
            font-size: 9px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- EN-TÊTE : Tableau invisible pour séparer FR et AR --}}
    <table class="header-table">
        <tr>
            <td class="header-fr">
                ROYAUME DU MAROC<br>
                MINISTERE DE L'ENSEIGNEMENT SUPERIEUR,<br>
                DE LA RECHERCHE SCIENTIFIQUE ET DE L'INNOVATION<br>
                UNIVERSITE SIDI MOHAMED BEN ABDELLAH<br>
                FACULTE DES SCIENCES DHAR EL MAHRAZ - FES
            </td>
            <td class="header-ar">
                المملكة المغربية<br>
                وزارة التعليم العالي والبحث العلمي والابتكار<br>
                جامعة سيدي محمد بن عبد الله<br>
                كلية العلوم ظهر المهراز - فاس
            </td>
        </tr>
    </table>

    {{-- TITRES --}}
    <div class="titre-ar">تكليـــــف بمهمـــــــة</div>
    <div class="titre">ORDRE DE MISSION</div>

    {{-- NUMERO --}}
    <div class="numero">
        {{ $numero_ordre }}
    </div>

    <div style="margin-bottom: 20px; font-weight: bold; font-size: 14px;">Le Doyen,</div>

    {{-- CORPS : Tableau invisible pour l'alignement strict --}}
    <table class="content-table">
        <tr>
            <td class="col-label">Ordonne à Mr / Mme :</td>
            <td class="col-value" style="text-transform: uppercase; font-weight: bold;">
                {{ $chauffeur }}
            </td>
        </tr>
        <tr>
            <td class="col-label">De se rendre à :</td>
            <td class="col-value">{{ $destination }}</td>
        </tr>
        <tr>
            <td class="col-label">Objet de la mission :</td>
            <td class="col-value">{{ $objet_mission }}</td>
        </tr>
        <tr>
            <td class="col-label">Moyen de transport :</td>
            <td class="col-value">{{ $transport }}</td>
        </tr>
        <tr>
            <td class="col-label">Personnel de la mission :</td>
            <td class="col-value">{{ $enseignants }}</td>
        </tr>
        <tr>
            <td class="col-label">Date de début :</td>
            <td class="col-value">{{ $date_debut }}</td>
        </tr>
        <tr>
            <td class="col-label">Date de la fin :</td>
            <td class="col-value">{{ $date_fin }}</td>
        </tr>
    </table>

    {{-- SIGNATURE --}}
    <table class="footer-table">
        <tr>
            <td style="width: 50%;"></td> {{-- Espace vide à gauche --}}
            <td style="width: 50%;" class="signature">
                <div style="margin-bottom: 10px; text-align: right; padding-right: 20px;">
                    {{ $date_creation }}
                </div>
                <div style="text-decoration: underline;">Signature du Doyen</div>
                <br><br><br><br>
            </td>
        </tr>
    </table>

    {{-- ADRESSE FIXE --}}
    <div class="footer-address">
        Faculté des Sciences Dhar El Mahraz - Fès; BP 1796 Fès-Atlas, Maroc<br>
        Tél.: 212 535 64 23 98/89/82 - Fax: 212 535 64 25 00 - Site web: www.fsdmfes.ac.ma
    </div>

</body>
</html>
