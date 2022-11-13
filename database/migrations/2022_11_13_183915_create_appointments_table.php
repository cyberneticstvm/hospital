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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->default(0);
            $table->string('patient_name', 25)->nullable();
            $table->string('gender', 8)->nullable();
            $table->integer('age')->default(0);
            $table->string('mobile_number', 10)->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('branch')->default(0);
            $table->unsignedBigInteger('doctor')->default(0);
            $table->date('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->unsignedBigInteger('created_by')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);
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
        Schema::dropIfExists('appointments');
    }
};
