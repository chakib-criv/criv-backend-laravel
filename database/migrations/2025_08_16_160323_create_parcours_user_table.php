<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcoursUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcours_user', function (Blueprint $table) {
            $table->id();

            // Clés étrangères vers users et parcours
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parcours_id')->constrained()->cascadeOnDelete();

            // Empêche les doublons (même user, même parcours)
            $table->unique(['user_id', 'parcours_id']);

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
        Schema::dropIfExists('parcours_user');
    }
}