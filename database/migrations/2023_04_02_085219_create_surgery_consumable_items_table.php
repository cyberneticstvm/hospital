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
        Schema::create('surgery_consumable_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surgery_id')->references('id')->on('surgery_types');
            $table->unsignedBigInteger('consumable_id')->references('id')->on('surgery_consumables');
            $table->integer('default_qty')->default(1);
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->unique(['surgery_id', 'consumable_id']);
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
        Schema::dropIfExists('surgery_consumable_items');
    }
};
