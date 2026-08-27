<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('demandes_consultations', function (Blueprint $table) {
            $table->enum('mode_consultation', ['presentiel', 'distance'])
                ->default('presentiel')
                ->after('urgence');
        });
    }

    public function down()
    {
        Schema::table('demandes_consultations', function (Blueprint $table) {
            $table->dropColumn('mode_consultation');
        });
    }
};
