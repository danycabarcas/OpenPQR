<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();                   // URL personalizada
            $table->string('sector')->nullable();               // Sector/actividad económica
            $table->string('logo_url')->nullable();             // Logo empresa
            $table->string('banner_url')->nullable();           // Banner personalizado (Premium)
            $table->string('color_primary', 20)->nullable();    // Color primario de la marca
            $table->string('email_contact')->nullable();        // Email de contacto
            $table->string('phone_contact')->nullable();        // Teléfono de contacto
            $table->string('address')->nullable();              // Dirección física/opcional
            $table->string('city')->nullable();                 // Ciudad
            $table->string('nit', 30)->nullable();              // NIT/Identificación tributaria

            // SI quieres dejar el plan actual (opcional, pero no recomendado como fuente principal)
            $table->unsignedBigInteger('plan_id')->nullable();

            $table->boolean('is_active')->default(true);        // Activa/Inactiva

            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
