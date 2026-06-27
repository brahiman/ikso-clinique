@extends('layouts.master')   {{-- ou ton layout principal Appzia --}}

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h2 class="mb-4">Tableau de bord Administrateur</h2>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Patients</p>
                                    <h4 class="mb-0">248</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <i class="ri-user-line font-size-24 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Médecins</p>
                                    <h4 class="mb-0">18</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <i class="ri-user-heart-line font-size-24 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ajoute d'autres cartes statistiques -->
            </div>

        </div>
    </div>
@endsection
