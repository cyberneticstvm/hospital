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
        Schema::create('pachymetries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->text('img1')->nullable();
            $table->string('img1_value', 150)->nullable();
            $table->text('img2')->nullable();
            $table->string('img2_value', 150)->nullable();
            $table->text('img3')->nullable();
            $table->string('img3_value', 150)->nullable();
            $table->text('img4')->nullable();
            $table->string('img4_value', 150)->nullable();
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('pachymetries');
    }
};
