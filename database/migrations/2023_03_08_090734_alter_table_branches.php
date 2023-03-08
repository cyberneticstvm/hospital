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
        Schema::table('branches', function(Blueprint $table){
            $table->integer('inhouse_camp_limit')->comment('Inhouse camp patient registration limit per day')->after('review_link')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function(Blueprint $table){
            $table->dropColumn('inhouse_camp_limit');
        });
    }
};
