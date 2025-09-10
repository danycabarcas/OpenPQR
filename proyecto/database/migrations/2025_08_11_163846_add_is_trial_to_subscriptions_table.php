<?php

// database/migrations/XXXX_XX_XX_XXXXXX_add_is_trial_to_subscriptions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('is_trial')->default(false)->after('plan_id');
            // Opcional: si quieres normalizar estatus cancelado en inglés:
            // $table->enum('status', ['active','expired','cancelled'])->default('active')->change();
        });
    }
    public function down(): void {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('is_trial');
        });
    }
};

