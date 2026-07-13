<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->foreignId('demande_consultation_id')->nullable()->constrained('demandes_consultations')->onDelete('set null');
            $table->dateTime('date_heure');
            $table->integer('duree')->default(30); // en minutes
            $table->enum('statut', ['planifie', 'confirme', 'en_cours', 'termine', 'annule', 'reporte'])->default('planifie');
            $table->text('motif')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
};
