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
        Schema::table('discharge_summary_medications', function (Blueprint $table) {
            $table->unsignedBigInteger('type')->after('medicine')->references('id')->on('medicine_types')->default(0);
            $table->integer('qty')->after('type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discharge_summary_medications', function (Blueprint $table) {
            $table->dropColumn(['type', 'qty']);
        });
    }
};
