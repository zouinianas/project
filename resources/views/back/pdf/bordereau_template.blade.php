<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Configuration de la police pour supporter l'Arabe */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            direction: rtl; /* Direction droite à gauche */
            text-align: right;
        }

        /* Mise en page globale */
        .container {
            width: 100%;
            padding: 10px;
        }

        /* En-tête (Header) : Tableau invisible à 3 colonnes */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            border: none;
        }
        .header-table td {
            vertical-align: top;
            text-align: center;
            border: none;
            padding: 0;
        }
        .header-ar { text-align: right; width: 35%; }
        .header-logo { text-align: center; width: 30%; }
        .header-fr { text-align: left; width: 35%; font-family: sans-serif; direction: ltr; }

        .bold { font-weight: bold; }
        .small { font-size: 11px; }

        /* Titre central */
        .main-title {
            text-align: center;
            border: 2px solid #000;
            padding: 5px 20px;
            display: inline-block;
            margin: 20px auto;
            font-weight: bold;
            font-size: 16px;
        }

        /* Section Informations (Numéro, Destinataire) */
        .info-row {
            margin-bottom: 10px;
            font-size: 15px;
        }
        .info-label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        /* Le Tableau Principal */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }
        .content-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        /* Signature */
        .signature-section {
            margin-top: 60px;
            margin-left: 50px; /* Décalage vers la gauche pour la signature */
            text-align: left;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-fr">
                <div class="bold">Royaume du Maroc</div>
                <div>Université Sidi Mohamed Ben Abdellah</div>
                <div>Faculté des Sciences</div>
                <div>Fès</div>
            </td>

            <td class="header-logo">
                <img src="{{ public_path('images/logo.png') }}" width="80" alt="Logo" style="margin-bottom: 10px;">
                <br>
                <div class="main-title">
                    ورقــة الإرســال<br>
                    BORDEREAU D'ENVOI
                </div>
            </td>

            <td class="header-ar">
                <div class="bold">المملكة المغربية</div>
                <div>جامعة سيدي محمد بن عبد الله</div>
                <div>كلية العلوم ظهر المهراز</div>
                <div>فاس</div>
            </td>
        </tr>
    </table>

    <br>

    <div class="container">
        <div class="info-row" style="text-align: center;">
            <span class="bold">فاس، في :</span> {{ $courrier->date_depart->format('d/m/Y') }} <span class="bold">: Fès le</span>
        </div>

        <div class="info-row">
            <span class="bold">من عميد كلية العلوم ظهر المهراز فاس</span>
        </div>

        <div class="info-row" style="margin-top: 15px;">
            <span class="bold">إلى السيد :</span> {{ $courrier->destinataire }}
        </div>

        <div class="info-row" style="margin-top: 15px;">
            <span class="bold">الرقم :</span> {{ $courrier->numero_ordre }} / {{ $courrier->annee }} <span class="bold">: N°</span>
        </div>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 50%;">ملاحظات<br>Observations</th>
                <th style="width: 15%;">عدد القطع<br>Nombre</th>
                <th style="width: 35%;">نوع الإرسال<br>Objet</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 300px;"> <td>
                    {{ $courrier->observation }}
                </td>
                <td style="text-align: center;">
                    {{ $courrier->nombre_pieces }}
                </td>
                <td>
                    {{ $courrier->objet }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        Le Doyen / العميد
    </div>

</body>
</html>
