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
        Schema::table('pachymetries', function(Blueprint $table){
            $table->string('od_iop', 8)->nullable()->after('branch');
            $table->string('od_cct', 8)->nullable()->after('od_iop');
            $table->string('od_ciop', 8)->nullable()->after('od_cct');
            $table->string('os_iop', 8)->nullable()->after('od_ciop');
            $table->string('os_cct', 8)->nullable()->after('os_iop');
            $table->string('os_ciop', 8)->nullable()->after('os_cct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pachymetries', function(Blueprint $table){
            $table->dropColumn('od_iop');
            $table->dropColumn('od_cct');
            $table->dropColumn('od_ciop');
            $table->dropColumn('os_iop');
            $table->dropColumn('os_cct');
            $table->dropColumn('os_ciop');
        });
    }
};
