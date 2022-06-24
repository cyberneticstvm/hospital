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
        Schema::create('lab_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->references('id')->on('lab_categories');
            $table->String('lab_type_name');
            $table->text('description')->nullable();
            $table->decimal('fee', 7, 2)->default('0.00');
            $table->unique(["category_id", "lab_type_name"], 'category_lab_type_name_unique');
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('lab_categories')->onDelete('cascade');
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
        Schema::dropIfExists('lab_types');
    }
};
