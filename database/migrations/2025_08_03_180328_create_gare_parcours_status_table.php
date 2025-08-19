<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGareParcoursStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gare_parcours_status', function (Blueprint $table) {
            $table->id();

            // LIGNES AJOUTÉES
            $table->foreignId('parcours_id')->constrained()->onDelete('cascade');
            $table->foreignId('gare_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true); // Par défaut, une gare est active

            $table->timestamps();

            // LIGNE AJOUTÉE : On s'assure que la combinaison d'un parcours et d'une gare est unique
            $table->unique(['parcours_id', 'gare_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gare_parcours_status');
    }
}