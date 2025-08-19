<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToLigneRersTable extends Migration
{
    public function up()
    {
        Schema::table('ligne_rers', function (Blueprint $table) {
            // On ajoute la colonne après la colonne 'color'
            $table->boolean('is_active')->default(true)->after('color');
        });
    }

    public function down()
    {
        Schema::table('ligne_rers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}