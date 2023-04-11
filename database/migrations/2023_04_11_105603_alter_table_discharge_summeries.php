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
        Schema::table('discharge_summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor')->after('special_instruction')->references('id')->on('doctors')->comment('Suregry Doctor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discharge_summaries', function (Blueprint $table) {
            $table->dropColumn('doctor');
        });
    }
};
