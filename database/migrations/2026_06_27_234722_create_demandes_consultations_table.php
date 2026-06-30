<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('demandes_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->text('motif');
            $table->text('symptomes')->nullable();
            $table->enum('urgence', ['basse', 'moyenne', 'haute'])->default('basse');
            $table->string('disponibilite_patient')->nullable();
            $table->enum('statut', ['en_attente', 'affectee', 'confirmee', 'terminee', 'annulee'])->default('en_attente');
            $table->foreignId('secretaire_id')->nullable()->constrained('users');
            $table->foreignId('medecin_id')->nullable()->constrained('medecins');
            $table->timestamp('date_affectation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes_consultations');
    }
};
