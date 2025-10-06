<?php
// database/migrations/2025_10_06_000000_create_themes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('themes', function (Blueprint $t) {
      $t->id();
      $t->string('name');
      $t->string('slug')->unique();
      $t->boolean('is_dark')->default(false);
      $t->json('variables'); // CSS vars en JSON (clave=>valor)
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('themes'); }
};
