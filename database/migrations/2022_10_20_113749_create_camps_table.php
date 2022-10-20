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
        Schema::create('camps', function (Blueprint $table) {
            $table->id();
            $table->String('patient_name', 50)->nullable();
            $table->integer('age')->default(0);
            $table->String('standard', 50)->nullable();
            $table->String('re_sph', 8)->nullable();
            $table->String('re_cyl', 8)->nullable();
            $table->String('re_axis', 8)->nullable();
            $table->String('re_add', 8)->nullable();
            $table->String('re_vb', 8)->nullable();
            $table->String('re_va', 8)->nullable();
            $table->String('le_sph', 8)->nullable();
            $table->String('le_cyl', 8)->nullable();
            $table->String('le_axis', 8)->nullable();
            $table->String('le_add', 8)->nullable();
            $table->String('le_vb', 8)->nullable();
            $table->String('le_va', 8)->nullable();
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->boolean('treatment_required')->default(0)->comments('0-Not Required, 1-Required');
            $table->boolean('specs_required')->default(0)->comments('0-Not Required, 1-Required');
            $table->date('camp_date')->nullable();
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
        Schema::dropIfExists('camps');
    }
};
