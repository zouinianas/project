<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bordereau N° {{ $courrier->numero_ordre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; /* Seule police gratuite qui supporte un peu l'arabe par défaut */ font-size: 14px; }
        .header-table { width: 100%; margin-bottom: 30px; border: none; }
        .header-table td { vertical-align: top; text-align: center; }
        .title { font-weight: bold; margin-bottom: 5px; }

        .content-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 10px; text-align: right; }
        .content-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }

        .info-section { margin: 20px 0; text-align: right; }
        .footer { margin-top: 50px; text-align: left; margin-left: 50px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 30%;">
                <div class="title">Royaume du Maroc</div>
                <div>Université Sidi Mohamed Ben Abdellah</div>
                <div>Faculté des Sciences</div>
                <div>Fès</div>
            </td>
            <td style="width: 40%;">
                <br><br>
                <h3>ورقــة الإرســال<br>BORDEREAU D'ENVOI</h3>
            </td>
            <td style="width: 30%; text-align: right;">
                <div class="title">المملكة المغربية</div>
                <div>جامعة سيدي محمد بن عبد الله</div>
                <div>كلية العلوم ظهر المهراز</div>
                <div>فاس</div>
            </td>
        </tr>
    </table>

    <div class="info-section">
        <p><strong>فاس، في :</strong> {{ $courrier->date_depart->format('d/m/Y') }} <strong>: Fès le</strong></p>
        <p><strong>الرقم :</strong> {{ $courrier->numero_ordre }}/{{ $courrier->annee }} <strong>: N°</strong></p>
        <p><strong>إلى السيد :</strong> {{ $courrier->destinataire }} <strong>: Destinataire</strong></p>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 50%">ملاحظات<br>Observations</th>
                <th style="width: 15%">عدد القطع<br>Nombre</th>
                <th style="width: 35%">نوع الإرسال<br>Objet</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $courrier->observation }}</td>
                <td style="text-align: center;">{{ $courrier->nombre_pieces }}</td>
                <td>{{ $courrier->objet }}</td>
            </tr>
            <tr style="height: 100px;">
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Le Doyen / العميد</strong></p>
        <br><br><br>
    </div>

</body>
</html>
