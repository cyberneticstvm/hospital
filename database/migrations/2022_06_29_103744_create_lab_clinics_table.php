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
        Schema::create('lab_clinics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_medical_records');
            $table->unsignedBigInteger('lab_type_id')->references('id')->on('lab_types');
            $table->text('lab_result')->nullable();
            $table->integer('tested_from')->default('1')->comment('1 for tested by Devi, 0 for tested from outside');  
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->foreign('medical_record_id')->references('id')->on('patient_medical_records')->onDelete('restrict');
            $table->foreign('lab_type_id')->references('id')->on('lab_types')->onDelete('restrict');
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
        Schema::dropIfExists('lab_clinics');
    }
};
