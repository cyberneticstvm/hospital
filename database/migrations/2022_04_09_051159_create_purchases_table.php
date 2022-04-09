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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product');
            $table->unsignedBigInteger('supplier');
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->String('invoice_number')->nullable();
            $table->tinyinteger('qty')->default('0');
            $table->decimal('price', 7, 2)->default('0.00');
            $table->decimal('total', 7, 2)->default('0.00');
            $table->String('batch_number')->nullable();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('purchases');
    }
};
