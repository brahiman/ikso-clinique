<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('sexe', ['M', 'F']);
            $table->date('date_naissance')->nullable();
            $table->string('telephone')->unique();
            $table->string('email')->nullable()->unique();
            $table->text('adresse')->nullable();
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Inconnu'])->default('Inconnu');
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
