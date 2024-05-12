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
        Schema::create('discharge_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_medical_records');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->date('doa')->nullable();
            $table->date('dos')->nullable();
            $table->date('dod')->nullable();
            $table->string('doa_time', 10)->nullable();
            $table->string('dos_time', 10)->nullable();
            $table->string('dod_time', 10)->nullable();
            $table->text('reason_for_admission')->nullable();
            $table->text('findings')->nullable();
            $table->text('investigation_result')->nullable();
            $table->text('general_examination')->nullable();
            $table->text('discharge_condition')->nullable();
            $table->string('medication', 25)->comment('Left eye only, Right eye only, Both')->nullable();
            $table->text('special_instruction')->nullable();
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->foreign('patient_id')->references('id')->on('patient_registrations')->onDelete('cascade');
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
        Schema::dropIfExists('discharge_summaries');
    }
};
