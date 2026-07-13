@extends('layouts.master')

@section('title', 'Clinique IA - Utilisateurs - Modification')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4">Modifier l'utilisateur</h2>

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

                    {{-- enctype="multipart/form-data" est indispensable pour l'envoi du fichier avatar --}}
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input class="form-control @error('name') is-invalid @enderror"
                                   type="text" name="name" value="{{ old('name', $user->name) }}"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror"
                                   type="email" name="email" value="{{ old('email', $user->email) }}"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input class="form-control @error('telephone') is-invalid @enderror"
                                   type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input class="form-control @error('adresse') is-invalid @enderror"
                                   type="text" name="adresse" value="{{ old('adresse', $user->adresse) }}"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date de naissance</label>
                            <input class="form-control @error('date_naissance') is-invalid @enderror"
                                   type="date" name="date_naissance"
                                   value="{{ old('date_naissance', optional($user->date_naissance)->format('Y-m-d')) }}"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <select class="form-select @error('sexe') is-invalid @enderror" name="sexe">
                                <option value="">--- Sélectionner un genre</option>
                                <option value="M" @selected(old('sexe', $user->sexe) == 'M')>Masculin</option>
                                <option value="F" @selected(old('sexe', $user->sexe) == 'F')>Féminin</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Matricule</label>
                            <input class="form-control @error('matricule') is-invalid @enderror"
                                   type="text" name="matricule" value="{{ old('matricule', $user->matricule) }}"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rôle(s)</label>
                            <div class="row">
                                @forelse($roles as $role)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input role-checkbox"
                                                type="checkbox"
                                                name="roles[]"
                                                value="{{ $role->id }}"
                                                id="role_{{ $role->id }}"
                                                data-role-slug="{{ \Illuminate\Support\Str::slug($role->name) }}"
                                                @checked(collect(old('roles', $userRoleIds))->contains($role->id))
                                            >
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">Aucun rôle disponible.</div>
                                @endforelse
                            </div>
                            @error('roles')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="specialites-container" style="display:none;">
                            <label class="form-label">Spécialité(s)</label>

                            <div class="row">
                                @forelse($specialites as $specialite)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="specialites[]"
                                                value="{{ $specialite->id }}"
                                                id="specialite_{{ $specialite->id }}"
                                                @checked(collect(old('specialites', $userSpecialiteIds))->contains($specialite->id))
                                            >
                                            <label class="form-check-label" for="specialite_{{ $specialite->id }}">
                                                {{ $specialite->nom }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">Aucune spécialité disponible.</div>
                                @endforelse
                            </div>
                            @error('specialites')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                                <option value="">--- Sélectionner un statut</option>
                                <option value="1" @selected(old('is_active', (string) (int) $user->is_active) === '1')>
                                    Actif
                                </option>
                                <option value="0" @selected(old('is_active', (string) (int) $user->is_active) === '0')>
                                    Inactif
                                </option>
                            </select>
                        </div>

                        <div class="mb-3 text-center">
                            <label class="form-label d-block">Avatar</label>
                            <img id="avatar-preview"
                                 src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/images/users/user-dummy.jpg') }}"
                                 alt="Aperçu de l'avatar"
                                 class="rounded-circle mb-2"
                                 style="width:110px;height:110px;object-fit:cover;border:1px solid #eef0f2;">
                            <div>
                                <input class="form-control @error('avatar') is-invalid @enderror"
                                       type="file" name="avatar" id="avatar-input"
                                       accept="image/png,image/jpeg,image/webp"
                                       style="max-width:320px;margin:0 auto;">
                                <div class="form-text">JPEG, PNG ou WEBP — 2 Mo maximum. Laissez vide pour conserver
                                    l'avatar actuel.
                                </div>
                                @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-success">
                            Mettre à jour
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const specialitesContainer = document.getElementById('specialites-container');
            const roleCheckboxes = document.querySelectorAll('.role-checkbox');

            function toggleSpecialites() {
                let afficher = false;

                roleCheckboxes.forEach(role => {
                    if (role.checked && role.dataset.roleSlug === 'medecin') {
                        afficher = true;
                    }
                });

                specialitesContainer.style.display = afficher ? 'block' : 'none';
            }

            roleCheckboxes.forEach(role => {
                role.addEventListener('change', toggleSpecialites);
            });

            // Affiche le bloc dès le chargement si "médecin" est déjà coché
            toggleSpecialites();

            // --- Aperçu instantané du nouvel avatar sélectionné ---
            const avatarInput = document.getElementById('avatar-input');
            const avatarPreview = document.getElementById('avatar-preview');

            avatarInput.addEventListener('change', function () {
                const fichier = this.files[0];
                if (!fichier) return;

                const lecteur = new FileReader();
                lecteur.onload = e => avatarPreview.src = e.target.result;
                lecteur.readAsDataURL(fichier);
            });
        });
    </script>
@endpush
