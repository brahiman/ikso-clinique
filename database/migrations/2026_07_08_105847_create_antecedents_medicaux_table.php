<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('antecedents_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['maladie', 'allergie', 'operation', 'vaccin', 'familial', 'autre']);
            $table->string('nom');
            $table->text('description')->nullable();
            $table->date('date_evenement')->nullable();
            $table->enum('gravite', ['faible', 'moyenne', 'haute'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('antecedents_medicaux');
    }
};
