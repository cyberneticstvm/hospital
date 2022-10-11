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
        Schema::create('tonometries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->String('nct_od', 3)->nullable();
            $table->String('nct_os', 3)->nullable();
            $table->String('nct_time', 10)->nullable();
            $table->String('at_od', 3)->nullable();
            $table->String('at_os', 3)->nullable();
            $table->String('at_time', 10)->nullable();
            $table->decimal('fee', 6, 2)->default(0.00);
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
        Schema::dropIfExists('tonometries');
    }
};
