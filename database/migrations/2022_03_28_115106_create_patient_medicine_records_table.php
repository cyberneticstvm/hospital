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
        Schema::create('patient_medicine_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('mrn');
            $table->foreign('medical_record_id')->references('id')->on('patient_medical_records')->onDelete('cascade');
            $table->String('medicine');
            $table->text('dosage');
            $table->String('dosage1');
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
        Schema::dropIfExists('patient_medicine_records');
    }
};
