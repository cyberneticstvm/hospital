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
        Schema::create('patient_registrations', function (Blueprint $table) {
            $table->id();
            $table->String('patient_name');
            $table->String('patient_id')->unique();
            $table->String('gender');
            $table->date('dob');
            $table->String('contact_person_name');
            $table->String('mobile_number');
            $table->String('email');
            $table->text('address');
            $table->integer('city');
            $table->integer('state');
            $table->integer('country');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('patient_registrations');
    }
};
