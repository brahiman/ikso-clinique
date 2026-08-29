{{--
    PDF Ordonnance — Clinique IKSô
    Généré via Barryvdh\DomPDF. DomPDF ne supporte qu'un sous-ensemble de CSS :
    on utilise donc des tables pour la mise en page (pas de flexbox/grid).
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 20px 32px 60px 32px;
        }
        *{
            font-family: DejaVu Sans, sans-serif;
            box-sizing: border-box;
        }
        body{
            color:#152016;
            font-size:12px;
            margin:0;
        }

        .header-table{
            width:100%;
            border-collapse:collapse;
            margin-bottom:6px;
        }
        .header-table td{
            vertical-align:middle;
        }
        .clinic-name{
            font-size:20px;
            font-weight:bold;
            color:#153a26;
            margin:0;
        }
        .clinic-tagline{
            font-size:10px;
            font-style:italic;
            color:#c1272d;
            margin:2px 0 0 0;
        }
        .header-right{
            text-align:right;
            font-size:9.5px;
            color:#555;
            line-height:1.5;
        }

        .ribbon-line{
            height:4px;
            background-color:#c1272d;
            margin:8px 0 16px 0;
        }

        .doc-title{
            text-align:center;
            font-size:15px;
            font-weight:bold;
            letter-spacing:1px;
            text-transform:uppercase;
            color:#153a26;
            border:1.5px solid #153a26;
            border-radius:4px;
            padding:6px 0;
            margin-bottom:14px;
        }

        .info-table{
            width:100%;
            border-collapse:collapse;
            margin-bottom:16px;
        }
        .info-table td{
            vertical-align:top;
            font-size:11px;
            padding:2px 0;
        }
        .info-label{
            color:#666;
            width:38%;
        }
        .info-value{
            font-weight:bold;
            color:#152016;
        }
        .info-col{
            width:50%;
            padding-right:14px;
            vertical-align:top;
        }
        .info-block-title{
            font-size:10px;
            text-transform:uppercase;
            letter-spacing:.5px;
            color:#2e8b45;
            font-weight:bold;
            border-bottom:1px solid #d7e8da;
            padding-bottom:3px;
            margin-bottom:6px;
        }

        table.rx{
            width:100%;
            border-collapse:collapse;
            margin-bottom:16px;
        }
        table.rx thead th{
            background-color:#153a26;
            color:#fff;
            font-size:10px;
            text-transform:uppercase;
            letter-spacing:.4px;
            padding:7px 8px;
            text-align:left;
        }
        table.rx tbody td{
            font-size:11px;
            padding:7px 8px;
            border-bottom:1px solid #e7e7e7;
        }
        table.rx tbody tr:nth-child(even){
            background-color:#f7faf7;
        }
        .rx-num{
            width:22px;
            color:#999;
        }

        .notes-box{
            border:1px solid #e2e2e2;
            border-radius:4px;
            background-color:#faf9f6;
            padding:10px 12px;
            font-size:11px;
            margin-bottom:22px;
        }
        .notes-title{
            font-size:10px;
            text-transform:uppercase;
            color:#c1272d;
            font-weight:bold;
            margin-bottom:4px;
        }

        .signature-table{
            width:100%;
            border-collapse:collapse;
            margin-top:30px;
        }
        .signature-table td{
            vertical-align:top;
            font-size:10px;
            color:#555;
        }
        .signature-box{
            text-align:right;
        }
        .signature-line{
            margin-top:40px;
            border-top:1px solid #999;
            width:180px;
            display:inline-block;
            padding-top:4px;
            text-align:center;
        }

        .footer{
            position:fixed;
            bottom:-40px;
            left:0;
            right:0;
            text-align:center;
            font-size:8.5px;
            color:#999;
            border-top:1px solid #eee;
            padding-top:6px;
        }
    </style>
</head>
<body>

    {{-- En-tête --}}
    <table class="header-table">
        <tr>
            <td style="width:70px;">
                <img src="{{ public_path('assets/images/logo-ikso1.png') }}" style="height:50px;">
            </td>
            <td>
                <p class="clinic-name">Clinique IKSô</p>
                <p class="clinic-tagline">Initiative Kênêya Sôrô-yôrô</p>
            </td>
            <td class="header-right">
                Ouagadougou, Burkina Faso<br>
                Tél : +226 XX XX XX XX<br>
                contact@clinique-ikso.bf
            </td>
        </tr>
    </table>
    <div class="ribbon-line"></div>

    <div class="doc-title">Ordonnance médicale</div>

    {{-- Infos patient / médecin --}}
    <table class="info-table">
        <tr>
            <td class="info-col">
                <div class="info-block-title">Patient</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Nom et prénom</td>
                        <td class="info-value">{{ $ordonnance->patient->nom ?? '' }} {{ $ordonnance->patient->prenom ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Date de naissance</td>
                        <td class="info-value">
                            {{ optional($ordonnance->patient->date_naissance)->format('d/m/Y') ?? '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Sexe</td>
                        <td class="info-value">{{ $ordonnance->patient->sexe ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Téléphone</td>
                        <td class="info-value">{{ $ordonnance->patient->telephone ?? '—' }}</td>
                    </tr>
                </table>
            </td>
            <td class="info-col">
                <div class="info-block-title">Prescripteur</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Médecin</td>
                        <td class="info-value">Dr {{ $ordonnance->medecin->user->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Spécialité(s)</td>
                        <td class="info-value">{{ $ordonnance->medecin->specialites->pluck('nom')->join(', ') ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Date de prescription</td>
                        <td class="info-value">{{ optional($ordonnance->date_prescription)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">N° ordonnance</td>
                        <td class="info-value">#{{ str_pad($ordonnance->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Tableau des médicaments --}}
    <table class="rx">
        <thead>
            <tr>
                <th class="rx-num">#</th>
                <th>Médicament</th>
                <th>Quantité</th>
                <th>Posologie</th>
                <th>Durée</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ordonnance->details as $i => $detail)
                <tr>
                    <td class="rx-num">{{ $i + 1 }}</td>
                    <td><strong>{{ $detail->medicament->nom ?? '—' }}</strong></td>
                    <td>{{ $detail->quantite }}</td>
                    <td>{{ $detail->frequence }}</td>
                    <td>{{ $detail->duree_jours }} jour(s)</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Instructions --}}
    @if ($ordonnance->notes)
        <div class="notes-box">
            <div class="notes-title">Instructions complémentaires</div>
            {{ $ordonnance->notes }}
        </div>
    @endif

    {{-- Signature --}}
    <table class="signature-table">
        <tr>
            <td style="width:60%;">
                Ce document est une prescription médicale.<br>
                À conserver et à présenter en pharmacie.
            </td>
            <td class="signature-box">
                <span class="signature-line">
                    Signature et cachet du médecin
                </span>
            </td>
        </tr>
    </table>

    <div class="footer">
        Clinique IKSô — Initiative Kênêya Sôrô-yôrô — Document généré électroniquement le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
