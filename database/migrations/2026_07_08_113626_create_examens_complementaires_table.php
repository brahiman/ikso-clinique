<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('examens_complementaires', function (Blueprint $table) {
            $table->id();

            // Patient concerné
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');

            // Lien avec la consultation (si l'examen est demandé pendant une consultation)
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');

            // Détails de l'examen
            $table->string('type_examen'); // Ex: Analyse sanguine, Radiographie thoracique, Échographie abdominale
            $table->text('description')->nullable();
            $table->text('resultats')->nullable();

            // Dates
            $table->date('date_demande');
            $table->date('date_resultat')->nullable();

            // Statut
            $table->enum('statut', ['demande', 'en_cours', 'termine', 'annule'])->default('demande');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examens_complementaires');
    }
};
