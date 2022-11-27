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
        Schema::create('post_operative_medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surgery_id');
            $table->unsignedBigInteger('medical_record_id')->references('id')->on('patient_medical_records');
            $table->unsignedBigInteger('patient')->references('id')->on('patient_registrations');
            $table->unsignedBigInteger('branch')->references('id')->on('branches');
            $table->string('type', 10)->nullable()->comments('postop, surgery');            
            $table->longText('notes')->nullable();
            $table->boolean('status')->default(1)->comments('1-Active, 0-Completed');
            $table->boolean('bill_generated')->default(0)->comments('1-Yes, 0-No');
            $table->foreign('surgery_id')->references('id')->on('surgeries')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
        Schema::create('post_operative_medicine_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pom_id');
            $table->unsignedBigInteger('product')->references('id')->on('products');
            $table->String('batch_number');
            $table->integer('qty');
            $table->decimal('price', 6,2)->default('0.00');
            $table->integer('tax')->default('0')->nullable();
            $table->decimal('tax_amount', 6, 2)->default('0.00')->nullable();
            $table->decimal('discount', 6, 2)->default('0.00')->nullable();
            $table->decimal('total', 6, 2)->default('0.00');
            $table->text('dosage')->nullable();
            $table->foreign('pom_id')->references('id')->on('post_operative_medicines')->onDelete('cascade');
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
        Schema::dropIfExists('post_operative_medicines');
        Schema::dropIfExists('post_operative_medicine_details');
    }
};
