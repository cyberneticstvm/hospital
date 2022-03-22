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
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            $table->String('country_name');
            $table->timestamps();
        });
        
        Schema::create('state', function (Blueprint $table) {
            $table->id();
            $table->String('state_name');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('country')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->String('city_name');
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('state')->onDelete('cascade');
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
        Schema::dropIfExists('country');
        Schema::dropIfExists('state');
        Schema::dropIfExists('city');
    }
};
