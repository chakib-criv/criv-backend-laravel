<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
Schema::create('gare_statuses', function (Blueprint $table) {
$table->id();
$table->foreignId('parcours_id')->constrained('parcours')->onDelete('cascade');
$table->foreignId('gare_id')->constrained('gares')->onDelete('cascade');
$table->boolean('is_active')->default(true);
$table->timestamps();
});
}

public function down(): void
{
Schema::dropIfExists('gare_statuses');
}
};