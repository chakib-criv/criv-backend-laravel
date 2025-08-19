<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gares', function (Blueprint $table) {
            $table->id(); // Colonne ID auto-incrémentée, clé primaire
            $table->string('name')->unique(); // Nom de la gare (doit être unique)

            // Clé étrangère vers la table 'ligne_rers'
            // UNSIGNED BIGINT pour correspondre au type de l'id par défaut de Laravel
            $table->foreignId('ligne_rer_id')
                  ->constrained('ligne_rers') // Assure que l'ID existe dans la table ligne_rers
                  ->onDelete('cascade'); // Si une ligne est supprimée, les gares associées le sont aussi

            $table->timestamps(); // Ajoute les colonnes 'created_at' et 'updated_at' automatiquement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('gares'); // Supprime la table 'gares' si la migration est annulée
    }
};
