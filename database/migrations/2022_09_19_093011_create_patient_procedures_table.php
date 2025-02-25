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
        Schema::create('patient_procedures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_medical_records');
            $table->unsignedBigInteger('procedure')->references('id')->on('procedures');
            $table->decimal('fee', 6, 2)->default(0.00);
            $table->decimal('discount', 6, 2)->default(0.00);
            $table->string('discount_category', 25)->nullable();
            $table->unsignedBigInteger('discount_category_id')->nullable();
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('patient_procedures');
    }
};
