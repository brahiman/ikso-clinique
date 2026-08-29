<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Clinique IKSô</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root{
            --ikso-forest-1:#0f2d1e;
            --ikso-forest-2:#153a26;
            --ikso-green-1:#2e8b45;
            --ikso-green-2:#6cc06a;
            --ikso-red:#c1272d;
            --ikso-red-dark:#8f1c21;
            --ikso-cream:#f8f6f1;
            --ikso-ink:#152016;
        }
        *{ font-family: -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        .font-display{ font-weight:600; letter-spacing:-0.01em; }

        .brand-panel{
            background:
                radial-gradient(circle at 15% 15%, rgba(108,192,106,0.18), transparent 45%),
                linear-gradient(155deg, var(--ikso-forest-1) 0%, var(--ikso-forest-2) 55%, #0b2116 100%);
        }

        /* Ruban rouge qui reprend la courbe du logo */
        .ribbon{
            position:absolute;
            left:-10%;
            right:-10%;
            bottom:14%;
            height:64px;
            background:linear-gradient(90deg, var(--ikso-red-dark), var(--ikso-red) 45%, var(--ikso-red-dark));
            transform:rotate(-3deg);
            box-shadow:0 10px 30px -8px rgba(0,0,0,0.5);
        }
        .ribbon::before,
        .ribbon::after{
            content:"";
            position:absolute;
            top:100%;
            width:0; height:0;
            border-style:solid;
        }
        .ribbon::before{
            left:0;
            border-width:10px 14px 0 0;
            border-color:#5c1013 transparent transparent transparent;
        }
        .ribbon::after{
            right:0;
            border-width:10px 0 0 14px;
            border-color:#5c1013 transparent transparent transparent;
        }

        /* Ligne de battement de cœur — signature reprise du ruban du logo */
        .ekg-path{
            fill:none;
            stroke:rgba(255,255,255,0.85);
            stroke-width:2.5;
            stroke-linecap:round;
            stroke-linejoin:round;
            stroke-dasharray:520;
            stroke-dashoffset:520;
            animation:draw-ekg 2.2s 0.4s ease-out forwards;
        }
        @media (prefers-reduced-motion: reduce){
            .ekg-path{ animation:none; stroke-dashoffset:0; }
        }
        @keyframes draw-ekg{
            to{ stroke-dashoffset:0; }
        }

        .input-field{
            transition:border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        }
        .input-field:focus{
            outline:none;
            border-color:var(--ikso-green-1);
            box-shadow:0 0 0 4px rgba(46,139,69,0.15);
            background-color:#fff;
        }

        .btn-login{
            background:linear-gradient(135deg, var(--ikso-green-1), #256b37);
            transition:transform .12s ease, box-shadow .12s ease, filter .12s ease;
        }
        .btn-login:hover{
            filter:brightness(1.06);
            box-shadow:0 10px 22px -8px rgba(46,139,69,0.55);
        }
        .btn-login:active{
            transform:translateY(1px);
        }
        .btn-login:focus-visible{
            outline:3px solid #a8d5ac;
            outline-offset:2px;
        }

        a:focus-visible, button:focus-visible, input:focus-visible{
            outline:3px solid var(--ikso-green-2);
            outline-offset:2px;
        }
    </style>
</head>
<body class="min-h-screen bg-[var(--ikso-cream)]">

    <div class="min-h-screen lg:grid lg:grid-cols-[1.05fr_1fr]">

        {{-- ============ Panneau de marque (gauche) ============ --}}
        <div class="brand-panel relative overflow-hidden text-white flex flex-col justify-between px-8 py-10 sm:px-14 sm:py-14 lg:min-h-screen">

            <div class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('assets/images/logo-ikso1.png') }}" alt="Clinique IKSô" class="h-14 w-auto drop-shadow-lg">
            </div>

            <div class="relative z-10 max-w-md mt-14 lg:mt-0">
                <p class="uppercase tracking-[0.25em] text-xs text-[var(--ikso-green-2)] font-semibold mb-4">
                    Espace sécurisé
                </p>
                <h1 class="font-display text-3xl sm:text-4xl font-semibold leading-tight">
                    Prendre soin, <br class="hidden sm:block"> ensemble.
                </h1>
                <p class="mt-4 text-white/70 text-sm sm:text-base leading-relaxed">
                    Connectez-vous pour accéder au dossier de vos patients, à vos rendez-vous
                    et à la gestion quotidienne de la clinique.
                </p>

                <svg viewBox="0 0 320 60" class="mt-8 w-full max-w-xs" aria-hidden="true">
                    <path class="ekg-path" d="M0,32 L55,32 L68,32 L78,10 L92,54 L104,20 L114,32 L150,32 L162,32 L172,14 L186,50 L198,26 L208,32 L320,32" />
                </svg>
            </div>

            <div class="relative z-10 mt-14 lg:mt-0">
                <p class="font-display italic text-white/90 text-sm sm:text-base">
                    « Initiative Kênêya Sôrô‑yôrô »
                </p>
            </div>

            <div class="ribbon"></div>
        </div>

        {{-- ============ Panneau du formulaire (droite) ============ --}}
        <div class="flex items-center justify-center px-6 py-12 sm:px-10 lg:px-16">
            <div class="w-full max-w-sm">

                <h2 class="font-display text-2xl font-semibold text-[var(--ikso-ink)]">
                    Connexion
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Entrez vos identifiants pour accéder à votre espace.
                </p>

                {{-- Message de statut (ex : réinitialisation de mot de passe) --}}
                @if (session('status'))
                    <div class="mt-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-[var(--ikso-ink)] mb-1.5">
                            Adresse e‑mail
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="vous@clinique-ikso.bf"
                            class="input-field w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-[var(--ikso-ink)] placeholder:text-gray-400"
                        >
                        @error('email')
                            <p class="mt-1.5 text-sm text-[var(--ikso-red)]">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-[var(--ikso-ink)]">
                                Mot de passe
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-[var(--ikso-green-1)] hover:text-[var(--ikso-red)] rounded">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-field w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-[var(--ikso-ink)] placeholder:text-gray-400"
                        >
                        @error('password')
                            <p class="mt-1.5 text-sm text-[var(--ikso-red)]">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Se souvenir de moi --}}
                    <label for="remember_me" class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-gray-300 text-[var(--ikso-green-1)] focus:ring-[var(--ikso-green-1)]"
                        >
                        <span class="text-sm text-gray-600">Se souvenir de moi</span>
                    </label>

                    <button type="submit" class="btn-login w-full rounded-xl py-3 text-sm font-semibold text-white shadow-md">
                        Se connecter
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    Clinique IKSô — Initiative Kênêya Sôrô‑yôrô
                </p>
            </div>
        </div>
    </div>
</body>
</html>