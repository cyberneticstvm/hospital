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
        Schema::create('doctor_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedInteger("medical_record_id")->nullable();
            $table->unsignedBigInteger("procedure_id")->nullable();
            $table->decimal("amount", 7, 2)->default(0);
            $table->string("type")->comment("dr, cr");
            $table->text("comment")->nullable();
            $table->boolean("notification")->comment("will be true when trigger the sms notification")->default(false);
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
        Schema::dropIfExists('doctor_accounts');
    }
};
