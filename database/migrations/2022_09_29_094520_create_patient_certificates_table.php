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
        Schema::create('patient_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mrn');
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('doctor_id')->references('id')->on('doctors');
            $table->unsignedBigInteger('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->foreign('mrn')->references('id')->on('patient_references')->onDelete('cascade');
            $table->foreign('medical_record_id')->references('id')->on('patient_medical_records')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('patient_certificate_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_certificate_id');
            $table->unsignedBigInteger('certificate_type')->references('id')->on('certificate_types')->default(0);
            $table->decimal('fee', 6, 2)->default(0.00);
            $table->char('status', 1)->default('N')->comments('I-Issued, R-Rejected, N-Not Issued');
            $table->text('notes')->nullable();
            $table->foreign('patient_certificate_id')->references('id')->on('patient_certificates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_certificates');
        Schema::dropIfExists('patient_certificate_details');
    }
};
