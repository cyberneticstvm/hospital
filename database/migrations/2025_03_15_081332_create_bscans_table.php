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
        Schema::create('bscans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('receipt_number')->default(0);
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('branch_id')->references('id')->on('branches');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->foreign('medical_record_id')->references('id')->on('patient_medical_records')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bscans');
    }
};
