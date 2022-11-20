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
        Schema::table('patient_payments', function(Blueprint $table){
            $table->unsignedBigInteger('pharmacy_id')->default(0)->after('medical_record_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_payments', function(Blueprint $table){
            $table->dropColumn('pharmacy_id')->default(0)->after('status');
        });
    }
};
