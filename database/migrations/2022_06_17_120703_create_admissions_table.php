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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('doctor_id')->references('id')->on('doctors');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('room_type')->references('id')->on('rooms');
            $table->integer('room_number')->default('0');
            $table->String('bystander_name')->nullable();
            $table->String('bystander_contact_number')->nullable();
            $table->String('patient_bystander_relation')->nullable();
            $table->text('remarks')->nullable();
            $table->dateTime('admission_date');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('admissions');
    }
};
