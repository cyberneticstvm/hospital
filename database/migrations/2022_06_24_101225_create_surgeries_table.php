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
        Schema::create('surgeries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('doctor_id')->references('id')->on('doctors');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->dateTime('surgery_date')->nullable();
            $table->unsignedBigInteger('surgery_type')->references('id')->on('surgery_types')->nullable();
            $table->unsignedBigInteger('surgeon')->references('id')->on('doctors')->default('0');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('updated_by')->references('id')->on('users')->default('0');
            $table->foreign('medical_record_id')->references('id')->on('patient_medical_records')->onDelete('cascade');
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
        Schema::dropIfExists('surgeries');
    }
};
