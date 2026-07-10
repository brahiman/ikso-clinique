<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordonnance_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ordonnance_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('medicament_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('dosage_prescrit')->nullable();   // Exemple : 500 mg

            $table->string('quantite')->nullable();          // Exemple : 2 comprimés

            $table->string('frequence')->nullable();         // Exemple : 3 fois/jour

            $table->string('moment')->nullable();            // Avant repas, Après repas...

            $table->integer('duree_jours')->nullable();

            $table->text('instructions')->nullable();        // Conseils particuliers

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordonnance_details');
    }
};