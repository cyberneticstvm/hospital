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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id')->comment('Either pharmacy out / medicine')->nullable();
            $table->enum('source', ['Pharmacy', 'Medicine'])->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('stock_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_returns');
    }
};
