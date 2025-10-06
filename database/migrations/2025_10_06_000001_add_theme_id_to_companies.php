<?php
// database/migrations/2025_10_06_000001_add_theme_id_to_companies.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('companies', function (Blueprint $t) {
      $t->foreignId('theme_id')->nullable()->constrained('themes')->nullOnDelete();
    });
  }
  public function down(): void {
    Schema::table('companies', function (Blueprint $t) {
      $t->dropConstrainedForeignId('theme_id');
    });
  }
};
