@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.master')

@section('title', 'Clinique IA - Utilisateurs - Détail')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h2 class="mb-4">{{ $user->name }}</h2>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Détails de l'utilisateur</h5>
                            <p><strong>Nom complet :</strong> {{ $user->name ?? '' }}</p>
                            <p><strong>Email :</strong> {{ $user->email ?? '' }}</p>
                            <p><strong>Téléphone :</strong> {{ $user->telephone ?? '' }}</p>
                            <p><strong>Date de naissance :</strong> {{ $user->date_naissance ?? '' }}</p>
                            <p><strong>Genre :</strong> {{ $user->sexe ?? '' }}</p>

                            <p><strong>Rôle(s) :</strong>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-info text-dark">{{ $role->name }}</span>
                                @empty
                                    Aucun rôle
                                @endforelse
                            </p>

                            @if($user->hasRole('medecin'))
                                <p><strong>Spécialité(s) :</strong>
                                    @forelse($user->medecin?->specialites ?? [] as $specialite)
                                        <span class="badge bg-primary">{{ $specialite->nom }}</span>
                                    @empty
                                        Aucune spécialité renseignée
                                    @endforelse
                                </p>
                            @endif

                            <p><strong>Statut :</strong>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    @if($user->is_active)
                        <div class="alert alert-success">
                            Cet utilisateur est actif !
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            Cet utilisateur est désactivé !
                        </div>
                    @endif

                    <div>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-success">
                            Modifier
                        </a>

                        <form action="{{ route('admin.users.passwordReset', $user->id) }}" method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Voulez-vous vraiment modifier le mot de passe de cet utilisateur ?');">
                            @csrf

                            <button type="submit" class="btn btn-outline-warning">
                                Réinitialiser
                            </button>
                        </form>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                Supprimer
                            </button>
                        </form>

                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Retour
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
