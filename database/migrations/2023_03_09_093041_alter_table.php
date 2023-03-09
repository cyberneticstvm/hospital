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
        Schema::table('inhouse_camps', function(Blueprint $table){
            $table->integer('validity')->after('to_date')->on('to_date')->comment('Procedure Validity in Days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inhouse_camps', function(Blueprint $table){
            $table->dropColumn('validity');
        });
    }
};
