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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->references('session_id')->on('users');
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->string('device', 50)->nullable();
            $table->string('ip', 25)->nullable();
            $table->string('location', 100)->nullable();
            $table->dateTime('logged_in')->nullable();
            $table->dateTime('logged_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_logs');
    }
};
