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
        Schema::create('letter_heads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->date('date')->nullable();
            $table->text('from')->nullable();
            $table->text('to')->nullable();
            $table->text('subject')->nullable();
            $table->longText('matter')->nullable();            
            $table->text('description')->nullable();            
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('letter_heads');
    }
};
