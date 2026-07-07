<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {   // ou table::table si tu modifies une existante
            $table->id();

            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');

            // Peut être null si consultation directe / urgence
            $table->foreignId('rendez_vous_id')->nullable()->constrained('rendez_vous')->onDelete('set null');

            $table->dateTime('date_consultation');

            $table->text('diagnostic')->nullable();
            $table->longText('observations')->nullable();
            $table->longText('traitement')->nullable();
            $table->longText('recommandations')->nullable();

            $table->enum('statut', ['en_cours', 'terminee'])->default('en_cours');

            // Nouveaux champs pour les consultations directes
            $table->boolean('est_urgence')->default(false);
            $table->text('motif_direct')->nullable();   // Motif quand pas de RDV préalable
            $table->text('notes_accueil')->nullable();  // Notes prises par la secrétaire à l'accueil

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('consultations');
    }
};
