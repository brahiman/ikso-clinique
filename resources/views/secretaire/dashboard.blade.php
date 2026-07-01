@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4">Accueil Secrétaire</h2>

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Demandes en attente</h5>
                            <h3 class="text-warning">12</h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Rendez-vous aujourd’hui</h5>
                            <h3 class="text-info">8</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
