<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossiers_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained()->onDelete('cascade');
            $table->text('antecedents_familiaux')->nullable();
            $table->text('allergies')->nullable();
            $table->text('vaccins')->nullable();
            $table->text('notes_generales')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossiers_medicaux');
    }
};
