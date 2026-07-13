<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       Schema::create('demande_examens', function (Blueprint $table) {
    $table->id();

    $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

    $table->foreignId('consultation_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

    $table->date('date_demande');

    $table->text('instructions')->nullable();

    $table->enum('statut', [
        'demande',
        'en_cours',
        'termine',
        'annule'
    ])->default('demande');

    $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('demande_examens');
    }
};
