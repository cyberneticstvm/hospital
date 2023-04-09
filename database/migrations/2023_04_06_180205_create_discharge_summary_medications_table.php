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
        Schema::create('discharge_summary_medications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summary_id');
            $table->unsignedBigInteger('medicine')->references('id')->on('products');
            $table->string('notes')->nullable();
            $table->foreign('summary_id')->references('id')->on('discharge_summaries')->onDelete('cascade');
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
        Schema::dropIfExists('discharge_summary_medications');
    }
};
