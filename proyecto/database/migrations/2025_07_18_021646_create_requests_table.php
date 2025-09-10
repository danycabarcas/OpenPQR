<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('department_id');
            $table->string('type', 10); // P, Q, R, S, D
            $table->string('subject', 200);
            $table->text('description');
            $table->string('tracking_code', 40)->unique();
            $table->string('status', 20)->default('nueva');
            $table->dateTime('response_due_date')->nullable();
            $table->string('created_via', 20)->default('web');
            $table->boolean('is_anonymous')->default(false);
            $table->string('citizen_type_document', 30)->nullable();
            $table->string('citizen_document', 30)->nullable();
            $table->string('citizen_name', 100)->nullable();
            $table->string('citizen_lastname', 100)->nullable();
            $table->string('citizen_phone', 30)->nullable();
            $table->string('citizen_email', 150)->nullable();
            $table->string('citizen_address', 150)->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
