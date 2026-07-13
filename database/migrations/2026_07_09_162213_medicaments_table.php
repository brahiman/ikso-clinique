<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('forme')->nullable();       // Comprimé, Sirop, Gélule...
            $table->string('dosage')->nullable();      // 500 mg, 1 g...
            $table->string('fabricant')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};