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
        Schema::create('patient_surgery_consumable_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psc_id');
            $table->unsignedBigInteger('consumable_id')->references('id')->on('surgery_consumables');
            $table->decimal('cost', 7, 2)->default(0.00);
            $table->integer('qty')->default(0);
            $table->decimal('total', 7, 2)->default(0.00);
            $table->foreign('psc_id')->references('id')->on('patient_surgery_consumables')->onDelete('cascade');
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
        Schema::dropIfExists('patient_surgery_consumable_lists');
    }
};
