<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patient_medecin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Empêche les doublons
            $table->unique(['patient_id', 'medecin_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_medecin');
    }
};
