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
            $table->unsignedBigInteger('patient_id');
            $table->String('arm_od_sph')->nullable();
            $table->String('arm_od_cyl')->nullable();
            $table->String('arm_od_axis')->nullable();
            $table->String('arm_os_sph')->nullable();
            $table->String('arm_os_cyl')->nullable();
            $table->String('arm_os_axis')->nullable();
            $table->String('pgp_od_sph')->nullable();
            $table->String('pgp_od_cyl')->nullable();
            $table->String('pgp_od_axis')->nullable();
            $table->String('pgp_od_add')->nullable();
            $table->String('pgp_od_vision')->nullable();
            $table->String('pgp_od_nv')->nullable();
            $table->String('pgp_os_sph')->nullable();
            $table->String('pgp_os_cyl')->nullable();
            $table->String('pgp_os_axis')->nullable();
            $table->String('pgp_os_add')->nullable();
            $table->String('pgp_os_vision')->nullable();
            $table->String('pgp_os_nv')->nullable();
            $table->String('dr_od_sph')->nullable();
            $table->String('dr_od_cyl')->nullable();
            $table->String('dr_od_axis')->nullable();
            $table->String('dr_od_add')->nullable();
            $table->String('dr_od_vision')->nullable();
            $table->String('dr_od_nv')->nullable();
            $table->String('dr_os_sph')->nullable();
            $table->String('dr_os_cyl')->nullable();
            $table->String('dr_os_axis')->nullable();
            $table->String('dr_os_add')->nullable();
            $table->String('dr_os_vision')->nullable();
            $table->String('dr_os_nv')->nullable();
            $table->foreign('patient_id')->references('id')->on('patient_registrations')->onDelete('restrict');
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
            //$table->dropColumn('patient_id');
            $table->dropColumn('arm_od_sph');
            $table->dropColumn('arm_od_cyl');
            $table->dropColumn('arm_od_axis');
            $table->dropColumn('arm_os_sph');
            $table->dropColumn('arm_os_cyl');
            $table->dropColumn('arm_os_axis');
            $table->dropColumn('pgp_od_sph');
            $table->dropColumn('pgp_od_cyl');
            $table->dropColumn('pgp_od_axis');
            $table->dropColumn('pgp_od_add');
            $table->dropColumn('pgp_od_vision');
            $table->dropColumn('pgp_od_nv');
            $table->dropColumn('pgp_os_sph');
            $table->dropColumn('pgp_os_cyl');
            $table->dropColumn('pgp_os_axis');
            $table->dropColumn('pgp_os_add');
            $table->dropColumn('pgp_os_vision');
            $table->dropColumn('pgp_os_nv');
            $table->dropColumn('dr_od_sph');
            $table->dropColumn('dr_od_cyl');
            $table->dropColumn('dr_od_axis');
            $table->dropColumn('dr_od_add');
            $table->dropColumn('dr_od_vision');
            $table->dropColumn('dr_od_nv');
            $table->dropColumn('dr_os_sph');
            $table->dropColumn('dr_os_cyl');
            $table->dropColumn('dr_os_axis');
            $table->dropColumn('dr_os_add');
            $table->dropColumn('dr_os_vision');
            $table->dropColumn('dr_os_nv');
        });
    }
};
