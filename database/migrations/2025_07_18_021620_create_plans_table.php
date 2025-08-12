<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // Nombre del plan
            $table->string('slug')->unique();             // Slug para uso interno/URL
            $table->text('description')->nullable();      // Descripción
            $table->decimal('price', 12, 2)->default(0);  // Precio mensual/anual
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->integer('max_departments')->nullable();       // Cantidad máxima de departamentos
            $table->integer('max_users')->nullable();             // Máx usuarios por empresa
            $table->integer('max_requests')->nullable();          // Máx PQRSD al mes
            $table->boolean('custom_colors')->default(false);     // ¿Permite personalizar colores?
            $table->boolean('custom_banner')->default(false);     // ¿Permite banner personalizado?
            $table->boolean('priority_support')->default(false);  // ¿Soporte prioritario?
            $table->json('features')->nullable();                 // Para futuras características extras
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
