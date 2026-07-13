@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.master')

@section('title', 'Clinique IA - Utilisateurs - Changement de mot de passe')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h2 class="mb-4">Changement de mot de passe</h2>

            <div class="card">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.passwordUpdate', Auth::user()->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="password_old">Mot de passe actuel</label>
                            <input class="form-control @error('password_old') is-invalid @enderror"
                                   type="password" name="password_old" value="{{ old('password_old') }}"/>

                            @error('password_old')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Nouveau mot de passe</label>
                            <input class="form-control @error('password') is-invalid @enderror"
                                   type="password" name="password" value="{{ old('password') }}"/>

                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirmer le nouveau mot de
                                passe</label>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                   type="password" name="password_confirmation"
                                   value="{{ old('password_confirmation') }}"/>

                            @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" name="action" value="continuer" class="btn btn-outline-success">
                            Enregistrer
                        </button>

                        <a href="{{ route('users.profil', Auth::user()->id) }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
