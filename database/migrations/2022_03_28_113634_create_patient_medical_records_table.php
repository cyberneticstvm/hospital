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
        Schema::create('patient_medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mrn');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('mrn')->references('id')->on('patient_references');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('patient_id')->references('id')->on('patient_registrations')->onDelete('cascade');
            $table->String('symptoms');
            $table->text('patient_complaints');
            $table->String('diagnosis');
            $table->text('doctor_findings');
            $table->text('doctor_recommondations');
            $table->text('medicine_list');
            $table->date('review_date');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('patient_medical_records');
    }
};
