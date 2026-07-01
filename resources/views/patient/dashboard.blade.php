@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4">Bienvenue, {{ auth()->user()->name }}</h2>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Prochain rendez-vous</h5>
                            <p>Pas de rendez-vous programmé</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
