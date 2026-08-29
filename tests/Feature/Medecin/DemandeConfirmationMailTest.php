<?php

namespace Tests\Feature\Medecin;

use App\Mail\ConsultationDistanceMail;
use App\Models\DemandeConsultation;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DemandeConfirmationMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_distance_consultation_confirmation_sends_email_to_patient(): void
    {
        Mail::fake();

        Role::firstOrCreate(['name' => 'medecin']);

        /** @var \App\Models\User $medecinUser */
        $medecinUser = User::factory()->create();
        $medecinUser->assignRole('medecin');

        $medecin = Medecin::create([
            'user_id' => $medecinUser->id,
            'matricule' => 'MED-001',
            'telephone' => '0600000001',
            'disponibilite' => [],
            'statut' => 'actif',
        ]);

        $patient = Patient::create([
            'nom' => 'Dupont',
            'prenom' => 'Claire',
            'sexe' => 'F',
            'date_naissance' => '1995-04-10',
            'telephone' => '0600000002',
            'email' => 'claire@example.com',
            'adresse' => 'Paris',
            'created_by' => $medecinUser->id,
        ]);

        $demande = DemandeConsultation::create([
            'patient_id' => $patient->id,
            'service_souhaite' => 'Consultation générale',
            'motif' => 'Suivi',
            'symptomes' => 'Maux de tête',
            'urgence' => 'moyenne',
            'mode_consultation' => 'distance',
            'disponibilite_patient' => 'Lundi 10h-12h',
            'statut' => 'affectee',
            'medecin_id' => $medecin->id,
        ]);

        $response = $this->actingAs($medecinUser)->post(route('medecin.demandes.confirmer', $demande), [
            'date_heure' => now()->addDay()->format('Y-m-d\TH:i'),
            'duree' => 30,
            'notes' => 'Lien Zoom : https://zoom.example.com/123',
        ]);

        $response->assertRedirect(route('medecin.demandes.index'));

        Mail::assertQueued(ConsultationDistanceMail::class, function ($mail) use ($patient) {
            return $mail->hasTo($patient->email);
        });
    }
}
