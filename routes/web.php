<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
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
    });

    /*Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // ou ton contrôleur
        })->name('dashboard');
    });
*/
    Route::middleware(['role:secretaire'])->prefix('secretaire')->name('secretaire.')->group(function () {
        Route::get('/dashboard', function () {
            return view('secretaire.dashboard');
        })->name('dashboard');

        Route::get('/demandes', [App\Http\Controllers\Secretaire\DemandeController::class, 'index'])->name('demandes.index');
        Route::post('/demandes/{demande}/affecter', [App\Http\Controllers\Secretaire\DemandeController::class, 'affecter'])->name('demandes.affecter');
        Route::resource('patients', \App\Http\Controllers\Secretaire\PatientController::class)->only(['index', 'create', 'store']);
        Route::post('patients/{patient}/affecter', [App\Http\Controllers\Secretaire\PatientController::class, 'affecter'])->name('patients.affecter');

        // Route pour le formulaire d'affectation
        Route::get('patients/{patient}/affecter', [App\Http\Controllers\Secretaire\PatientController::class, 'affecterForm'])
            ->name('patients.affecter-form');

    });

    Route::middleware(['role:medecin'])->prefix('medecin')->name('medecin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('medecin.dashboard');
        })->name('dashboard');
    });

    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', function () {
            return view('patient.dashboard');
        })->name('dashboard');

        Route::get('/demande-consultation', [App\Http\Controllers\Patient\DemandeController::class, 'create'])->name('demandes.create');
        Route::post('/demande-consultation', [App\Http\Controllers\Patient\DemandeController::class, 'store'])->name('demandes.store');
        Route::get('/mes-demandes', [App\Http\Controllers\Patient\DemandeController::class, 'index'])->name('demandes.index');

    });

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
