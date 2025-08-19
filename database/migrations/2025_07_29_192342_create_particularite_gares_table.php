<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticulariteGaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('particularite_gares', function (Blueprint $table) {
            $table->id();
            $table->text('texte');

            // Clé étrangère vers la table 'gares'
            $table->foreignId('gare_id')
                  ->constrained('gares')
                  ->onDelete('cascade');

            // Clé étrangère vers la table 'parcours'
            // MODIFICATION ICI : On ajoute ->nullable() pour la rendre optionnelle
            $table->foreignId('parcours_id')
                  ->nullable() 
                  ->constrained('parcours')
                  ->onDelete('cascade');

            // Clé étrangère vers la table 'users'
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('particularite_gares');
    }
};