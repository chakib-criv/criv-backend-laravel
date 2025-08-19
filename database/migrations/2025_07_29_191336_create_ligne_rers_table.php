<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneRersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_rers', function (Blueprint $table) {
            $table->id(); // Colonne ID auto-incrémentée, clé primaire
            $table->string('name')->unique(); // Nom de la ligne (ex: "RER A", "Transilien H") - doit être unique
            $table->string('color')->nullable(); // Couleur associée à la ligne (ex: "#CE0037") - peut être vide
            $table->timestamps(); // Ajoute les colonnes 'created_at' et 'updated_at' automatiquement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ligne_rers'); // Supprime la table 'ligne_rers' si la migration est annulée
    }
};
