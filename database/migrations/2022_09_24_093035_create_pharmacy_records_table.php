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
        Schema::create('pharmacy_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('cascade');
            $table->unsignedBigInteger('product')->references('id')->on('products');
            $table->unsignedBigInteger('category')->references('id')->on('product_categories');
            $table->unsignedBigInteger('type')->references('id')->on('medicine_types');
            $table->String('batch_number');
            $table->integer('qty');
            $table->decimal('price', 6,2)->default('0.00');
            $table->integer('tax')->default('0')->nullable();
            $table->decimal('tax_amount', 6, 2)->default('0.00')->nullable();
            $table->decimal('discount', 6,2)->default('0.00')->nullable();
            $table->decimal('total', 6,2)->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacy_records');
    }
};
