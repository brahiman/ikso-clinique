<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Specialite;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'matricule', 'email', 'telephone', 'is_active')
            ->with('roles')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::select('id', 'name', 'email', 'telephone', 'avatar', 'adresse', 'date_naissance', 'sexe', 'matricule', 'is_active')
            ->with(['roles', 'medecin.specialites'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::select('id', 'name')
            ->orderBy('name')
            ->get();

        $specialites = Specialite::select('id', 'nom')
            ->orderBy('nom')
            ->get();

        return view('admin.users.create', compact('roles', 'specialites'));
    }

    public function store()
    {
        $data = request()->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'telephone' => 'nullable|string|max:255',
                'adresse' => 'nullable|string',
                'date_naissance' => 'nullable|date',
                'sexe' => 'nullable|in:M,F,Autre',
                'matricule' => 'nullable|string|unique:users,matricule',
                'is_active' => 'nullable|boolean',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
                'specialites' => 'nullable|array',
                'specialites.*' => 'exists:specialites,id',
            ],
            [
                'name.required' => 'Le nom complet est obligatoire.',
                'name.string' => 'Le nom doit être une chaîne de caractères.',
                'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',

                'email.required' => 'L\'adresse email est obligatoire.',
                'email.email' => 'Veuillez saisir une adresse email valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',

                'telephone.string' => 'Le téléphone doit être une chaîne de caractères.',
                'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 255 caractères.',

                'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',

                'date_naissance.date' => 'La date de naissance doit être une date valide.',

                'sexe.in' => 'Le sexe sélectionné est invalide.',

                'matricule.unique' => 'Ce matricule existe déjà.',
                'matricule.string' => 'Le matricule doit être une chaîne de caractères.',

                'is_active.boolean' => 'Le statut actif/inactif est invalide.',

                'roles.required' => 'Veuillez sélectionner au moins un rôle.',
                'roles.array' => 'Le format des rôles sélectionnés est invalide.',
                'roles.*.exists' => 'Un des rôles sélectionnés n\'existe pas.',

                'specialites.array' => 'Le format des spécialités sélectionnées est invalide.',
                'specialites.*.exists' => 'Une des spécialités sélectionnées n\'existe pas.',
            ]
        );

        $medecinRoleId = Role::where('name', 'medecin')->value('id');
        $estMedecin = $medecinRoleId && in_array($medecinRoleId, $data['roles']);

        // Un médecin doit avoir au moins une spécialité
        if ($estMedecin && empty($data['specialites'])) {
            return back()
                ->withErrors(['specialites' => 'Veuillez sélectionner au moins une spécialité pour un médecin.'])
                ->withInput();
        }

        $default_password = 'password123';

        if (in_array(Role::where('name', 'admin')->value('id'), $data['roles'])) {
            $default_password = 'passwordAdmin123';
        } elseif (in_array(Role::where('name', 'secretaire')->value('id'), $data['roles'])) {
            $default_password = 'passwordSecretaire123';
        } elseif (in_array(Role::where('name', 'medecin')->value('id'), $data['roles'])) {
            $default_password = 'passwordMedecin123';
        } elseif (in_array(Role::where('name', 'patient')->value('id'), $data['roles'])) {
            $default_password = 'passwordPatient123';
        }

        $user = DB::transaction(function () use ($data, $estMedecin, $default_password) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'telephone' => $data['telephone'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'sexe' => $data['sexe'] ?? null,
                'matricule' => $data['matricule'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'password' => bcrypt($default_password),
            ]);

            $user->roles()->sync($data['roles']);

            if ($estMedecin) {
                $medecin = Medecin::create([
                    'user_id' => $user->id,
                    'matricule' => $user->matricule,
                    'telephone' => $user->telephone,]);
                $medecin->specialites()->sync($data['specialites'] ?? []);
            }

            return $user;
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit($id)
    {
        $user = User::with(['roles', 'medecin.specialites'])->findOrFail($id);

        $roles = Role::select('id', 'name')->orderBy('name')->get();
        $specialites = Specialite::select('id', 'nom')->orderBy('nom')->get();

        $userRoleIds = $user->roles->pluck('id')->toArray();
        $userSpecialiteIds = $user->medecin?->specialites->pluck('id')->toArray() ?? [];

        return view('admin.users.edit', compact('user', 'roles', 'specialites', 'userRoleIds', 'userSpecialiteIds'));
    }

    public function update($id)
    {
        $user = User::findOrFail($id);

        $medecinRoleId = Role::where('name', 'medecin')->value('id');

        $data = request()->validate(
            [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id)
                ],
                'telephone' => 'nullable|string|max:255',
                'adresse' => 'nullable|string',
                'date_naissance' => 'nullable|date',
                'sexe' => 'nullable|in:M,F,Autre',
                'matricule' => [
                    'required',
                    'string',
                    Rule::unique('users', 'matricule')->ignore($user->id)
                ],
                'is_active' => 'nullable|boolean',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
                'specialites' => 'required_if:roles.*,' . $medecinRoleId . '|array',
                'specialites.*' => 'exists:specialites,id',
            ],
            [
                'name.required' => 'Le nom complet est obligatoire.',
                'name.string' => 'Le nom doit contenir uniquement du texte.',
                'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',

                'email.required' => 'L\'adresse email est obligatoire.',
                'email.email' => 'Veuillez saisir une adresse email valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée par un autre utilisateur.',

                'telephone.string' => 'Le numéro de téléphone doit être du texte.',
                'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 255 caractères.',

                'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',

                'date_naissance.date' => 'La date de naissance doit être une date valide.',

                'sexe.in' => 'Le sexe sélectionné doit être Masculin, Féminin ou Autre.',

                'matricule.required' => 'Le matricule est obligatoire.',
                'matricule.string' => 'Le matricule doit être une chaîne de caractères.',
                'matricule.unique' => 'Ce matricule est déjà utilisé par un autre utilisateur.',

                'is_active.boolean' => 'Le statut sélectionné est invalide.',

                'roles.required' => 'Veuillez sélectionner au moins un rôle.',
                'roles.array' => 'Les rôles sélectionnés sont invalides.',
                'roles.*.exists' => 'Un rôle sélectionné n\'existe pas.',

                'specialites.array' => 'Les spécialités sélectionnées sont invalides.',
                'specialites.*.exists' => 'Une spécialité sélectionnée n\'existe pas.',
            ]
        );

        $medecinRoleId = Role::where('name', 'medecin')->value('id');
        $estMedecin = $medecinRoleId && in_array($medecinRoleId, $data['roles']);

        if ($estMedecin && empty($data['specialites'])) {
            return back()
                ->withErrors(['specialites' => 'Veuillez sélectionner au moins une spécialité pour un médecin.'])
                ->withInput();
        }

        DB::transaction(function () use ($user, $data, $estMedecin) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'telephone' => $data['telephone'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'sexe' => $data['sexe'] ?? null,
                'matricule' => $data['matricule'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            $user->roles()->sync($data['roles']);

            $medecin = $user->medecin;

            if ($estMedecin) {

                if (!$medecin) {
                    $medecin = Medecin::create([
                        'user_id' => $user->id,
                        'matricule' => $user->matricule,
                        'telephone' => $user->telephone,
                    ]);
                } else {
                    $medecin->update([
                        'matricule' => $user->matricule,
                        'telephone' => $user->telephone,
                    ]);
                }

                $medecin->specialites()->sync($data['specialites'] ?? []);

            } elseif ($medecin) {

                $medecin->specialites()->sync([]);
                $medecin->delete();

            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = User::with('medecin')->findOrFail($id);

        DB::transaction(function () use ($user) {
            // Soft delete de la fiche médecin associée, si elle existe
            $user->medecin?->delete();

            // Soft delete de l'utilisateur (SoftDeletes du modèle User)
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        // Restaure la fiche médecin associée si elle a été soft-deletée en même temps
        Medecin::withTrashed()->where('user_id', $user->id)->first()?->restore();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur restauré avec succès.');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        DB::transaction(function () use ($user) {
            Medecin::withTrashed()->where('user_id', $user->id)->first()?->forceDelete();
            $user->forceDelete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé définitivement.');
    }

    public function passwordReset($id)
    {
        $user = User::findOrFail($id);

        $default_password = 'password123';

        if ($user->hasRole('admin')) {
            $default_password = 'passwordAdmin123';
        } elseif ($user->hasRole('secretaire')) {
            $default_password = 'passwordSecretaire123';
        } elseif ($user->hasRole('medecin')) {
            $default_password = 'passwordMedecin123';
        } elseif ($user->hasRole('patient')) {
            $default_password = 'passwordPatient123';
        }

        $user->password = bcrypt($default_password);

        $user->save();

        return back()->with('success', 'Le mot de passe de l\'utilisateur a été réinitialisé avec succès.');
    }
}
