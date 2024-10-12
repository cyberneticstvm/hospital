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
        Schema::create('royalty_card_procedures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proc_id');
            $table->unsignedBigInteger('royalty_card_id');
            $table->integer('discount_percentage');
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
        Schema::dropIfExists('royalty_card_procedures');
    }
};
