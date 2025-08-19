<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Le nom de la classe peut être différent si vous utilisez une version plus récente de Laravel, 
// mais le contenu des fonctions up() et down() est le plus important.
class CreateLigneRerParcoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_rer_parcours', function (Blueprint $table) {
            $table->id();

            // LIGNE AJOUTÉE : Crée la colonne pour l'ID du parcours
            // constrained() lie automatiquement à la table 'parcours' (colonne 'id')
            // onDelete('cascade') supprime cette ligne si le parcours associé est supprimé
            $table->foreignId('parcours_id')->constrained()->onDelete('cascade');

            // LIGNE AJOUTÉE : Crée la colonne pour l'ID de la ligne RER
            // constrained() lie automatiquement à la table 'ligne_rers' (colonne 'id')
            $table->foreignId('ligne_rer_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ligne_rer_parcours');
    }
}