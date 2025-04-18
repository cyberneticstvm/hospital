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
        Schema::create('promotion_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('template_id')->unique();
            $table->string('template_language')->nullable();
            $table->date('scheduled_date');
            $table->enum('entity', ['hospital', 'store', 'lab']);
            $table->integer('sms_limit_per_hour');
            $table->unsignedBigInteger('branch_id')->default(0);
            $table->enum('status', ['publish', 'draft']);
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
        Schema::dropIfExists('promotion_schedules');
    }
};
