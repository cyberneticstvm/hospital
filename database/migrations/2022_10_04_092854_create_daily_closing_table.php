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
        Schema::create('daily_closing', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('closing_balance', 7, 2);
            $table->unsignedBigInteger('branch')->references('id')->on('branches')->default(0);
            $table->unsignedBigInteger('closed_by')->references('id')->on('users')->nullable()->default(0);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('daily_closing');
    }
};
