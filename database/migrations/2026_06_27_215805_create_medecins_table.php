<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('specialite_id')->nullable()->constrained('specialites');
            $table->string('matricule')->unique();
            $table->string('telephone');
            $table->json('disponibilite')->nullable(); // Ex: {"lundi": ["08:00-12:00"]}
            $table->enum('statut', ['actif', 'en_conge', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medecins');
    }
};
