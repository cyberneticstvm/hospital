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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->String('description');
            $table->decimal('amount', 7, 2);
            $table->unsignedBigInteger('head')->references('id')->on('income_expense_heads')->onDelete('cascade');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('incomes');
    }
};
