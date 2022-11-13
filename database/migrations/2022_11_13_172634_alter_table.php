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
        Schema::table('settings', function (Blueprint $table) {
            $table->time('appointment_from_time')->nullable()->after('consultation_fee_days');
            $table->time('appointment_to_time')->nullable()->after('appointment_from_time');
            $table->integer('appointment_interval')->nullable()->after('appointment_to_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('appointment_from_time');
            $table->dropColumn('appointment_to_time');
            $table->dropColumn('appointment_interval');
        });
    }
};
