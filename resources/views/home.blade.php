@extends('layouts.master')

@section('content')
    <div class="min-vh-100 d-flex flex-column">
        <!-- Hero Section -->
        <section class="bg-light py-5">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="display-3 fw-bold text-primary mb-3">Clinique IKSô</h1>
                        <p class="lead fs-4 text-muted mb-4">
                            Votre santé entre de bonnes mains. Prenez rendez-vous en ligne en toute simplicité.
                        </p>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                                Créer mon compte
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                                Se connecter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4">
                            <div class="mb-4 text-primary">
                                <i class="ri-calendar-check-line display-1"></i>
                            </div>
                            <h5 class="fw-bold">Prise de Rendez-vous</h5>
                            <p class="text-muted">Choisissez votre médecin et réservez en quelques clics.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4">
                            <div class="mb-4 text-success">
                                <i class="ri-chat-3-line display-1"></i>
                            </div>
                            <h5 class="fw-bold">Chat IA Médical</h5>
                            <p class="text-muted">Obtenez des réponses rapides et fiables à vos questions de santé.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4">
                            <div class="mb-4 text-info">
                                <i class="ri-file-medical-line display-1"></i>
                            </div>
                            <h5 class="fw-bold">Dossier Médical en Ligne</h5>
                            <p class="text-muted">Accédez à votre historique médical en toute sécurité.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Appel à l'action -->
        <section class="py-5 bg-white">
            <div class="container text-center">
                <h3 class="mb-3">Prêt à prendre soin de votre santé ?</h3>
                <p class="text-muted mb-4">Rejoignez des milliers de patients qui font confiance à notre clinique.</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                    Commencer maintenant
                </a>
            </div>
        </section>
    </div>
@endsection
