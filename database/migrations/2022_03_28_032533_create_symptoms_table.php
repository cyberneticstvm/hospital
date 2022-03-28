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
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->String('symptom_name')->unique();
            $table->timestamps();
        });
        Schema::create('diagnosis', function (Blueprint $table) {
            $table->id();
            $table->String('diagnosis_name')->unique();
            $table->timestamps();
        });
        Schema::create('medicine', function (Blueprint $table) {
            $table->id();
            $table->String('medicine_name')->unique();
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
        Schema::dropIfExists('symptoms');
        Schema::dropIfExists('diagnosis');
        Schema::dropIfExists('medicine');
    }
};
