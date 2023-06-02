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
        Schema::table('ascans', function (Blueprint $table) {
            $table->text('aconst_od1')->after('eye')->nullable();
            $table->text('aconst_os1')->after('aconst_od1')->nullable();
            $table->text('iol_od1')->after('aconst_os1')->nullable();
            $table->text('iol_os1')->after('iol_od1')->nullable();
            $table->text('aconst_od2')->after('iol_os1')->nullable();
            $table->text('aconst_os2')->after('aconst_od2')->nullable();
            $table->text('iol_od2')->after('aconst_os2')->nullable();
            $table->text('iol_os2')->after('iol_od2')->nullable();
            $table->text('aconst_od3')->after('iol_os2')->nullable();
            $table->text('aconst_os3')->after('aconst_od3')->nullable();
            $table->text('iol_od3')->after('aconst_os3')->nullable();
            $table->text('iol_os3')->after('iol_od3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ascans', function (Blueprint $table) {
            $table->dropColumn(['aconst_od1', 'aconst_os1', 'iol_od1', 'iol_os1', 'aconst_od2', 'aconst_os2', 'iol_od2', 'iol_os2', 'aconst_od3', 'aconst_os3', 'iol_od3', 'iol_os3']);
        });
    }
};
