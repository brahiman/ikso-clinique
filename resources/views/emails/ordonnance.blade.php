<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background-color:#f8f6f1; font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f6f1; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden;">

                    {{-- En-tête vert --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#153a26,#0f2d1e); padding:28px 32px;">
                            <img src="{{ asset('assets/images/logo-ikso1.png') }}" alt="Clinique IKSô" height="40" style="display:block;">
                            
                        </td>
                    </tr>

                    {{-- Ruban rouge --}}
                    <tr>
                        <td style="height:4px; background-color:#c1272d; line-height:4px; font-size:0;">&nbsp;</td>
                    </tr>

                    {{-- Corps --}}
                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 12px 0; font-size:19px; color:#152016;">
                                Bonjour {{ $ordonnance->patient->prenom ?? '' }},
                            </h1>
                            <p style="margin:0 0 16px 0; font-size:14px; line-height:1.6; color:#3a3a3a;">
                                Suite à votre consultation du
                                {{ optional($ordonnance->date_prescription)->format('d/m/Y') }}
                                avec le Dr {{ $ordonnance->medecin->user->name ?? '' }}, veuillez trouver
                                ci-joint votre <strong>ordonnance médicale</strong> au format PDF.
                            </p>
                            <p style="margin:0 0 20px 0; font-size:14px; line-height:1.6; color:#3a3a3a;">
                                Merci de la présenter à la pharmacie lors du retrait de vos médicaments.
                            </p>

                            <div style="background-color:#f7faf7; border:1px solid #d7e8da; border-radius:8px; padding:14px 16px; font-size:13px; color:#153a26;">
                                📄 Ordonnance n° {{ str_pad($ordonnance->id, 6, '0', STR_PAD_LEFT) }} — {{ $ordonnance->details->count() }} médicament(s) prescrit(s)
                            </div>
                        </td>
                    </tr>

                    {{-- Pied de page --}}
                    <tr>
                        <td style="padding:20px 32px; background-color:#f8f6f1; text-align:center;">
                            <p style="margin:0; font-size:11px; color:#999;">
                                Clinique IKSô — Initiative Kênêya Sôrô-yôrô<br>
                                Ceci est un message automatique, merci de ne pas y répondre directement.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
