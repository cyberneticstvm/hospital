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
        Schema::create('camp_masters', function (Blueprint $table) {
            $table->id();
            $table->String('camp_id', 10)->unique();
            $table->unsignedBigInteger('type')->references('id')->on('camp_types')->onDelete('cascade');
            $table->String('venue', 50)->nullable();
            $table->String('address', 100)->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->unsignedBigInteger('cordinator')->references('id')->on('users');
            $table->String('optometrist', 25)->nullable();
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
        Schema::create('camp_types', function (Blueprint $table) {
            $table->id();            
            $table->String('name', 50)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('camp_masters');
    }
};
