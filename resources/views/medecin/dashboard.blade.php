@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4">Mon Tableau de bord - Dr. {{ auth()->user()->name }}</h2>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Patients suivis</h5>
                            <h3 class="text-primary">47</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Rendez-vous aujourd’hui</h5>
                            <h3 class="text-success">6</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
