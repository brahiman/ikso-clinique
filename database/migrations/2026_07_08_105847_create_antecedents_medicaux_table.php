<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antecedents_medicaux', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dossier_medical_id')
                ->constrained('dossiers_medicaux')
                ->cascadeOnDelete();

            $table->enum('type', [
                'maladie',
                'allergie',
                'operation',
                'vaccin',
                'familial',
                'autre'
            ]);

            $table->string('nom');

            $table->text('description')->nullable();

            $table->date('date_evenement')->nullable();

            $table->enum('gravite', [
                'faible',
                'moyenne',
                'haute'
            ])->nullable();

            $table->boolean('actif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antecedents_medicaux');
    }
};