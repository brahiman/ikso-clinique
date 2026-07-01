@extends('layouts.master')

@section('title', 'Dashboard - Clinique IA')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="mb-sm-0 ">Bienvenue sur le Tableau de Bord de la Clinique</h4>
        </div>
        <div class="card col-lg-3 m-3 col-sm-6">
            <div class="card-body">
                <h3 class="accordion">Affichage dynamique en fonction des roles</h3>
            </div>

        </div>
    </div>
    <div class="row">

        <div class="col-6">1</div>
        <div class="col-3">2</div>

    </div>

    <!-- Ici tu pourras mettre les stats : Nombre de patients, RDV aujourd'hui, etc. -->
@endsection
