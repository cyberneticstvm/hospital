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
        Schema::create('keratometries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('patient_id')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->String('k1_od_auto', 8)->nullable();
            $table->String('k1_os_auto', 8)->nullable();
            $table->String('k1_od_axis_a', 3)->nullable();
            $table->String('k1_os_axis_a', 3)->nullable();
            $table->String('k2_od_auto', 8)->nullable();
            $table->String('k2_os_auto', 8)->nullable();
            $table->String('k2_od_axis_a', 3)->nullable();
            $table->String('k2_os_axis_a', 3)->nullable();
            $table->String('k1_od_manual', 8)->nullable();
            $table->String('k1_os_manual', 8)->nullable();
            $table->String('k1_od_axis_m', 3)->nullable();
            $table->String('k1_os_axis_m', 3)->nullable();
            $table->String('k2_od_manual', 8)->nullable();
            $table->String('k2_os_manual', 8)->nullable();
            $table->String('k2_od_axis_m', 3)->nullable();
            $table->String('k2_os_axis_m', 3)->nullable();
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
        Schema::dropIfExists('keratometries');
    }
};
