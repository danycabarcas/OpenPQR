<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->integer('storage_gb')->nullable()->after('price');
            $table->enum('support_channel', ['tickets_email','whatsapp'])->default('tickets_email')->after('storage_gb');
            $table->boolean('allow_custom_domain')->default(false)->after('support_channel');
            $table->boolean('is_active')->default(true)->after('allow_custom_domain');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['slug','storage_gb','support_channel','allow_custom_domain','is_active']);
        });
    }
};
