@extends('layouts.master')

@section('title', 'Clinique IA - Utilisateurs - Liste')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4">Liste des utilisateurs</h2>

            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom complet</th>
                            <th>Matricule</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle(s)</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->matricule }}</td>
                                <td>
                                        {{ $user->email }}
                                </td>
                                <td>{{ $user->telephone }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        {{ $role->name }}
                                    @empty
                                        Aucun rôle
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active === true ? 'success' : 'danger' }}">
                                        {{ $user->is_active === true ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                                        {{ $user->id === 'affectee' ? 'Confirmer' : 'Voir' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">Aucune demande.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
