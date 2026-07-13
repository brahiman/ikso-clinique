<?php

use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Medecin\ConsultationController;
use App\Http\Controllers\Medecin\DemandeController;
use App\Http\Controllers\Medecin\MedecinDashboardController;
use App\Http\Controllers\Medecin\RendezVousController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::middleware('auth')->group(function () {

    // Redirection intelligente selon le rôle
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isSecretaire()) return redirect()->route('secretaire.dashboard');
        if ($user->isMedecin()) return redirect()->route('medecin.dashboard');
        if ($user->isPatient()) return redirect()->route('patient.dashboard');

        return view('dashboard');
    })->name('dashboard');

    // ==================== ROUTES PAR RÔLE ====================

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('/users', UserController::class);
        Route::post('/users/{id}/password/reset', [UserController::class, 'passwordReset'])->name('users.passwordReset');

        Route::prefix('statistiques')->name('statistiques.')->group(function () {

            // Export Excel générique (?type=... reprend les mêmes clés que les URLs ci-dessous)
            Route::get('/export', [StatistiqueController::class, 'export'])->name('export');

            // 1. Activité et flux de patients
            Route::get('/consultations-par-periode', [StatistiqueController::class, 'consultationsParPeriode'])->name('consultationsParPeriode');
            Route::get('/taux-consultations-urgentes', [StatistiqueController::class, 'tauxConsultationsUrgentes'])->name('tauxConsultationsUrgentes');
            Route::get('/statut-consultations', [StatistiqueController::class, 'statutConsultations'])->name('statutConsultations');
            Route::get('/statut-rendez-vous', [StatistiqueController::class, 'statutRendezVous'])->name('statutRendezVous');
            Route::get('/taux-no-show', [StatistiqueController::class, 'tauxNoShow'])->name('tauxNoShow');

            // 2. Demandes de consultation (entonnoir)
            Route::get('/entonnoir-demandes-consultations', [StatistiqueController::class, 'entonnoirDemandesConsultations'])->name('entonnoirDemandesConsultations');
            Route::get('/delai-moyen-affectation', [StatistiqueController::class, 'delaiMoyenAffectation'])->name('delaiMoyenAffectation');
            Route::get('/demandes-par-urgence', [StatistiqueController::class, 'demandesParUrgence'])->name('demandesParUrgence');
            Route::get('/demandes-par-service', [StatistiqueController::class, 'demandesParService'])->name('demandesParService');

            // 3. Démographie des patients
            Route::get('/pyramide-ages', [StatistiqueController::class, 'pyramideAges'])->name('pyramideAges');
            Route::get('/repartition-sexe', [StatistiqueController::class, 'repartitionSexe'])->name('repartitionSexe');
            Route::get('/repartition-groupe-sanguin', [StatistiqueController::class, 'repartitionGroupeSanguin'])->name('repartitionGroupeSanguin');

            // 4. Médecins et spécialités
            Route::get('/charge-travail-medecins', [StatistiqueController::class, 'chargeTravailMedecins'])->name('chargeTravailMedecins');
            Route::get('/repartition-medecins-par-specialite', [StatistiqueController::class, 'repartitionMedecinsParSpecialite'])->name('repartitionMedecinsParSpecialite');
            Route::get('/statut-medecins', [StatistiqueController::class, 'statutMedecins'])->name('statutMedecins');

            // 5. Examens médicaux
            Route::get('/types-examens-plus-demandes', [StatistiqueController::class, 'typesExamensPlusDemandes'])->name('typesExamensPlusDemandes');
            Route::get('/delai-moyen-examens', [StatistiqueController::class, 'delaiMoyenExamens'])->name('delaiMoyenExamens');
            Route::get('/statut-examens', [StatistiqueController::class, 'statutExamens'])->name('statutExamens');

            // 6. Prescriptions (ordonnances)
            Route::get('/medicaments-plus-prescrits', [StatistiqueController::class, 'medicamentsPlusPrescrits'])->name('medicamentsPlusPrescrits');
            Route::get('/nombre-moyen-medicaments-par-ordonnance', [StatistiqueController::class, 'nombreMoyenMedicamentsParOrdonnance'])->name('nombreMoyenMedicamentsParOrdonnance');
            Route::get('/repartition-forme-medicaments', [StatistiqueController::class, 'repartitionFormeMedicaments'])->name('repartitionFormeMedicaments');

            // 7. Antécédents médicaux
            Route::get('/antecedents-par-type', [StatistiqueController::class, 'antecedentsParType'])->name('antecedentsParType');
            Route::get('/antecedents-par-gravite', [StatistiqueController::class, 'antecedentsParGravite'])->name('antecedentsParGravite');
            Route::get('/pathologies-frequentes', [StatistiqueController::class, 'pathologiesFrequentes'])->name('pathologiesFrequentes');

            // 8. Vue d'ensemble / tableau de bord
            Route::get('/dashboard-kpis', [StatistiqueController::class, 'dashboardKpis'])->name('dashboardKpis');
            Route::get('/dashboard-complet', [StatistiqueController::class, 'dashboardComplet'])->name('dashboardComplet');
        });
        Route::resource('/statistiques', StatistiqueController::class)->only(['index', 'show']);
    });

    /*Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // ou ton contrôleur
        })->name('dashboard');
    });
*/
    Route::middleware(['role:secretaire'])->prefix('secretaire')->name('secretaire.')->group(function () {

        // Accueil Secrétaire (Dashboard)
        Route::get('/dashboard', [App\Http\Controllers\Secretaire\SecretaireDashboardController::class, 'index'])
            ->name('dashboard');

        // Demandes de consultation
        Route::get('/demandes', [App\Http\Controllers\Secretaire\DemandeController::class, 'index'])
            ->name('demandes.index');
        Route::post('/demandes/{demande}/affecter', [App\Http\Controllers\Secretaire\DemandeController::class, 'affecter'])
            ->name('demandes.affecter');
        Route::get('/demandes/{demande}', [App\Http\Controllers\Secretaire\DemandeController::class, 'show'])
            ->name('demandes.show');

        Route::get('/consultations/direct', [App\Http\Controllers\Secretaire\ConsultationController::class, 'createDirect'])->name('consultations.direct');
        Route::post('/consultations/direct', [App\Http\Controllers\Secretaire\ConsultationController::class, 'storeDirect'])->name('consultations.direct.store');

        Route::get('/consultations', [App\Http\Controllers\Secretaire\ConsultationController::class, 'index'])->name('consultations.index');


        Route::resource('patients', App\Http\Controllers\Secretaire\PatientController::class)
            ->only(['index', 'create', 'store']);

        Route::get('patients/{patient}/affecter', [App\Http\Controllers\Secretaire\PatientController::class, 'affecterForm'])
            ->name('patients.affecter-form');

        Route::post('patients/{patient}/affecter', [App\Http\Controllers\Secretaire\PatientController::class, 'affecter'])
            ->name('patients.affecter');
    });

    Route::middleware(['role:medecin'])->prefix('medecin')->name('medecin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('medecin.dashboard');
        })->name('dashboard');
    });
    // Patients
    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', function () {
            return view('patient.dashboard');
        })->name('dashboard');

        Route::get('/demande-consultation', [App\Http\Controllers\Patient\DemandeController::class, 'create'])->name('demandes.create');
        Route::post('/demande-consultation', [App\Http\Controllers\Patient\DemandeController::class, 'store'])->name('demandes.store');
        Route::get('/mes-demandes', [App\Http\Controllers\Patient\DemandeController::class, 'index'])->name('demandes.index');
        Route::get('/mes-rendez-vous',  [App\Http\Controllers\Patient\DemandeController::class, 'mesRendezVous'])->name('rendezvous');
        Route::get('/mes-consultations', [App\Http\Controllers\Patient\DemandeController::class, 'listeConsultation'])->name('consultations');


            Route::get('/dossier-medical', [App\Http\Controllers\Patient\DossierMedicalController::class, 'index'])->name('dossier.index');
            Route::get('/dossier-medical/{patient}', [App\Http\Controllers\Patient\DossierMedicalController::class, 'show'])->name('dossier.show');

    });
    Route::prefix('medecin')->name('medecin.')->middleware(['auth', 'role:medecin'])->group(function () {
        Route::get('/dashboard', [MedecinDashboardController::class, 'index'])->name('dashboard');

    Route::get('/rendez-vous', [RendezVousController::class, 'index'])->name('rendez-vous.index');
    Route::patch('/rendez-vous/{rendezVous}/statut', [RendezVousController::class, 'updateStatut'])->name('rendez-vous.statut');
    Route::post('/rendez-vous/{rendezVous}/demarrer', [RendezVousController::class, 'demarrerConsultation'])->name('rendez-vous.demarrer');
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{consultation}/edit', [ConsultationController::class, 'edit'])->name('consultations.edit');
    Route::put('/consultations/{consultation}', [ConsultationController::class, 'update'])->name('consultations.update');
    Route::get('/consultations/{consultation}', [ConsultationController::class, 'show'])->name('consultations.show');
    //routes pour  creer les ordonnances et les examens complémentaires
    Route::post('/consultations/{consultation}/ordonnances', [ConsultationController::class, 'storeOrdonnance'])->name('consultations.ordonnances.store');
    Route::post('/consultations/{consultation}/examens', [ConsultationController::class, 'storeDemandeExamen'])->name('consultations.examens.store');
    Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/confirmer', [DemandeController::class, 'confirmer'])->name('demandes.confirmer');
});

    // Profil
    Route::get('/user/{id}/profil/', [UserController::class, 'show'])->name('users.profil');
    Route::get('/user/password/change/', [UserController::class, 'passwordChange'])->name('users.passwordChange');
    Route::post('/user/{id}/password/update/', [UserController::class, 'passwordUpdate'])->name('users.passwordUpdate');
});

require __DIR__ . '/auth.php';
