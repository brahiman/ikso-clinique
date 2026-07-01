@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-4">Demandes de Consultation en Attente</h4>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Motif</th>
                        <th>Urgence</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($demandes as $demande)
                        <tr>
                            <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</td>
                            <td>{{ $demande->motif }}</td>
                            <td>{{ $demande->urgence }}</td>
                            <td>
                            <span class="badge bg-{{ $demande->statut == 'en_attente' ? 'warning' : 'success' }}">
                                {{ ucfirst($demande->statut) }}
                            </span>
                            </td>
                            <td>
                                @if($demande->statut == 'en_attente')
                                    <form action="{{ route('secretaire.demandes.affecter', $demande) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <select name="medecin_id" class="form-select form-select-sm d-inline w-auto">
                                            @foreach(App\Models\Medecin::with('user')->get() as $med)
                                                <option value="{{ $med->id }}">Dr. {{ $med->user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Affecter</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
