<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordonnances', function (Blueprint $table) {
            $table->timestamp('envoye_le')->nullable()->after('notes');
        });

        Schema::table('demande_examens', function (Blueprint $table) {
            $table->timestamp('envoye_le')->nullable()->after('instructions');
        });
    }

    public function down(): void
    {
        Schema::table('ordonnances', function (Blueprint $table) {
            $table->dropColumn('envoye_le');
        });

        Schema::table('demande_examens', function (Blueprint $table) {
            $table->dropColumn('envoye_le');
        });
    }
};
