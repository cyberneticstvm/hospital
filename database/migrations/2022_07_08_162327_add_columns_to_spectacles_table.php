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
        Schema::table('spectacles', function (Blueprint $table) {
            $table->String('bm_k1_od_a', 10)->nullable();
            $table->String('bm_k1_os_a', 10)->nullable();
            $table->String('bm_k2_od_a', 10)->nullable();
            $table->String('bm_k2_os_a', 10)->nullable();
            $table->String('bm_k1_od_m', 10)->nullable();
            $table->String('bm_k1_os_m', 10)->nullable();
            $table->String('bm_k2_od_m', 10)->nullable();
            $table->String('bm_k2_os_m', 10)->nullable();
            $table->String('bm_od_axl', 10)->nullable();
            $table->String('bm_os_axl', 10)->nullable();
            $table->String('bm_od_acd', 10)->nullable();
            $table->String('bm_os_acd', 10)->nullable();
            $table->String('bm_od_lens', 10)->nullable();
            $table->String('bm_os_lens', 10)->nullable();
            $table->String('bm_od_kvalue_a', 10)->nullable();
            $table->String('bm_os_kvalue_a', 10)->nullable();
            $table->String('bm_od_iol', 10)->nullable();
            $table->String('bm_os_iol', 10)->nullable();
            $table->String('iop_at_r', 10)->nullable();
            $table->String('iop_at_l', 10)->nullable();
            $table->String('iop_nct_time', 10)->nullable();
            $table->String('iop_at_time', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spectacles', function (Blueprint $table) {
            $table->dropColumn('bm_k1_od_a', 'bm_k1_os_a', 'bm_k2_od_a', 'bm_k2_os_a', 'bm_k1_od_m', 'bm_k1_os_m', 'bm_k2_od_m', 'bm_k2_os_m', 'bm_od_axl', 'bm_os_axl', 'bm_od_acd', 'bm_os_acd', 'bm_od_lens', 'bm_os_lens', 'bm_od_kvalue_a', 'bm_os_kvalue_a', 'bm_od_iol', 'bm_os_iol', 'iop_at_r', 'iop_at_l', 'iop_nct_time', 'iop_at_time');
        });
    }
};
