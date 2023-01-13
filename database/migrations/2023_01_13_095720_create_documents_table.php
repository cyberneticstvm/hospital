<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250)->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_references');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('documents');
    }
};
