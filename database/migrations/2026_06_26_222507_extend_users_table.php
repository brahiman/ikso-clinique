<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telephone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('telephone');
            $table->text('adresse')->nullable()->after('avatar');
            $table->date('date_naissance')->nullable()->after('adresse');
            $table->enum('sexe', ['M', 'F', 'Autre'])->nullable()->after('date_naissance');
            $table->string('matricule')->nullable()->unique()->after('sexe');
            $table->boolean('is_active')->default(true)->after('matricule');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');

            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telephone', 'avatar', 'adresse', 'date_naissance',
                'sexe', 'matricule', 'is_active', 'last_login_at',
                'last_login_ip'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
