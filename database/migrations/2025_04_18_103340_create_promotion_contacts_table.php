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
        Schema::create('promotion_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('contact_number', 15)->unique()->nullable();
            $table->enum('type', ['include', 'exclude']);
            $table->enum('entity', ['hospital', 'store', 'lab']);
            $table->string('wa_sms_status', 5)->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_contacts');
    }
};
