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
        Schema::create('patient_surgery_consumables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_medical_records');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->unsignedBigInteger('surgery_id')->references('id')->on('surgery_types');            
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('patient_surgery_consumables');
    }
};
