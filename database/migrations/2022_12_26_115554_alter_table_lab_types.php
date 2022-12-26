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
        Schema::table('lab_types', function(Blueprint $table){
            $table->unsignedBigInteger('surgery_type')->default(0)->after('fee');
            $table->integer('tested_from')->nullable()->after('surgery_type')->comments('1-Own Lab, 0-Outside Lab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('surgery_type');
        $table->dropColumn('tested_from');
    }
};
