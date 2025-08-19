<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcours', function (Blueprint $table) {
            $table->id(); // Colonne ID auto-incrémentée, clé primaire
            $table->string('name')->unique(); // Nom du parcours (doit être unique)
            $table->text('description')->nullable(); // Description du parcours (peut être vide)
            $table->timestamps(); // Ajoute les colonnes 'created_at' et 'updated_at' automatiquement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('parcours'); // Supprime la table 'parcours' si la migration est annulée
    }
};
