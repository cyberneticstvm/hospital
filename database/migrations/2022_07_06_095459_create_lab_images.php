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
        Schema::create('lab_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_medical_records')->default('0');
            $table->unsignedBigInteger('lab_test_id');
            $table->unsignedBigInteger('lab_type_id')->references('id')->on('lab_types');
            $table->longText('img')->nullable();
            $table->text('description')->nullable();
            $table->text('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_images');
    }
};
