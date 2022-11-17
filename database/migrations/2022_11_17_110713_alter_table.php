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
        Schema::table('patient_references', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->default(0)->after('status');
        });
        Schema::table('patient_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->default(0)->after('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_references', function (Blueprint $table) {
            $table->drop_column('appointment_id');
        });

        Schema::table('patient_registrations', function (Blueprint $table) {
            $table->drop_column('appointment_id');
        });
    }
};
