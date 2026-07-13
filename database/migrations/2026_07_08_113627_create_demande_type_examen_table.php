<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
      Schema::create('demande_type_examen', function (Blueprint $table) {

    $table->id();

    $table->foreignId('demandes_examen_id')->constrained()->cascadeOnDelete();

    $table->foreignId('types_examen_id')->constrained()->cascadeOnDelete();

    $table->text('observation')->nullable();

    $table->text('resultat')->nullable();

    $table->date('date_resultat')->nullable();

    $table->timestamps();

    });
    }

    public function down()
    {
        Schema::dropIfExists('demande_type_examen');
    }
};
