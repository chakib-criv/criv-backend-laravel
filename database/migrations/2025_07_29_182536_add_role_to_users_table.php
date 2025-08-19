<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
     {
        Schema::table('users', function (Blueprint $table) {
            // Ajoute la colonne 'role' de type string (texte)
            // avec une valeur par défaut 'user' (utilisateur simple)
            // et la positionne après la colonne 'password'
            $table->string('role')->default('user')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Si la migration est annulée, supprime la colonne 'role'
            $table->dropColumn('role');
        });
    }
};
