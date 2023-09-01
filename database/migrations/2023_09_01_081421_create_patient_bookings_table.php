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
        Schema::create('patient_bookings', function (Blueprint $table) {
            $table->id();
            $table->String('patient_name')->nullable();
            $table->String('patient_id')->nullable();
            $table->String('gender')->nullable();
            $table->integer('age')->nullable();
            $table->String('mobile_number');
            $table->text('address')->nullable();
            $table->string('otp', 6)->nullable();
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
        Schema::dropIfExists('patient_bookings');
    }
};
