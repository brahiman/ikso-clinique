<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background-color:#f8f6f1; font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f6f1; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="520" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#153a26,#0f2d1e); padding:28px 32px;">
                            <img src="{{ asset('assets/images/logo-ikso1.png') }}" alt="Clinique IKSô" height="40" style="display:block;">
                        </td>
                    </tr>

                    <tr>
                        <td style="height:4px; background-color:#2e8b45; line-height:4px; font-size:0;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 12px 0; font-size:19px; color:#152016;">
                                Bonjour {{ $demande->patient->prenom ?? '' }},
                            </h1>

                            <p style="margin:0 0 16px 0; font-size:14px; line-height:1.7; color:#3a3a3a;">
                                Votre rendez-vous de consultation à distance a été confirmé.
                                Le <strong>{{ optional($rendezVous->date_heure)->translatedFormat('l d F Y') }}</strong> à
                                <strong>{{ optional($rendezVous->date_heure)->format('H:i') }}</strong>.
                            </p>

                            <div style="background-color:#f7faf7; border:1px solid #d7e8da; border-radius:8px; padding:16px; margin:0 0 16px; font-size:14px; color:#153a26;">
                                <div style="font-weight:600; margin-bottom:8px;">Modalités de consultation</div>
                                @if(!empty($notes))
                                    <div style="white-space:pre-line; line-height:1.6;">{{ $notes }}</div>
                                @else
                                    <div>Le médecin vous donnera le mode exact de consultation à distance lors de votre appel.</div>
                                @endif
                            </div>

                            <p style="margin:0; font-size:14px; line-height:1.7; color:#3a3a3a;">
                                Merci de vous connecter à l’heure prévue et de vous assurer que votre environnement est prêt pour la consultation.
                            </p>
                        </td>
                    </tr>

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
