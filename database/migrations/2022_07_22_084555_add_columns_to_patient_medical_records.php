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
        Schema::table('patient_medical_records', function (Blueprint $table) {
            $table->longText('vision_od_img3')->nullable();
            $table->longText('vision_os_img3')->nullable();
            $table->longText('vision_od_img4')->nullable();
            $table->longText('vision_os_img4')->nullable();
            $table->string('sel_1_od', 50)->nullable()->comment('Appearance');
            $table->string('sel_1_os', 50)->nullable()->comment('Appearance');
            $table->string('sel_2_od', 50)->nullable()->comment('Extraocular Movements');
            $table->string('sel_2_os', 50)->nullable()->comment('Extraocular Movements');
            $table->string('sel_3_od', 50)->nullable()->comment('Orbital Margins');
            $table->string('sel_3_os', 50)->nullable()->comment('Orbital Margins');
            $table->string('sel_4_od', 50)->nullable()->comment('LID and Adnexa');
            $table->string('sel_4_os', 50)->nullable()->comment('LID and Adnexa');
            $table->string('sel_5_od', 50)->nullable()->comment('Conjunctiva');
            $table->string('sel_5_os', 50)->nullable()->comment('Conjunctiva');
            $table->string('sel_6_od', 50)->nullable()->comment('Sclera');
            $table->string('sel_6_os', 50)->nullable()->comment('Sclera');
            $table->string('sel_7_od', 50)->nullable()->comment('Cornea');
            $table->string('sel_7_os', 50)->nullable()->comment('Cornea');
            $table->string('sel_8_od', 50)->nullable()->comment('Anterior Chamber');
            $table->string('sel_8_os', 50)->nullable()->comment('Anterior Chamber');
            $table->string('sel_9_od', 50)->nullable()->comment('Iris');
            $table->string('sel_9_os', 50)->nullable()->comment('Iris');
            $table->string('sel_10_od', 50)->nullable()->comment('Pupil');
            $table->string('sel_10_os', 50)->nullable()->comment('Pupil');
            $table->string('sel_11_od', 50)->nullable()->comment('Lens');
            $table->string('sel_11_os', 50)->nullable()->comment('Lens');
            $table->string('sel_12_od', 50)->nullable()->comment('AVR');
            $table->string('sel_12_os', 50)->nullable()->comment('AVR');
            $table->string('sel_13_od', 50)->nullable()->comment('Fundus');
            $table->string('sel_13_os', 50)->nullable()->comment('Fundus');
            $table->string('sel_14_od', 50)->nullable()->comment('Media');
            $table->string('sel_14_os', 50)->nullable()->comment('Media');
            $table->string('od_disc_margins', 50)->nullable();
            $table->string('os_disc_margins', 50)->nullable();
            $table->string('od_cdr', 50)->nullable();
            $table->string('os_cdr', 50)->nullable();
            $table->string('od_nrr', 50)->nullable();
            $table->string('os_nrr', 50)->nullable();
            $table->string('od_av_ratio', 50)->nullable();
            $table->string('os_av_ratio', 50)->nullable();
            $table->string('od_fr', 50)->nullable();
            $table->string('os_fr', 50)->nullable();
            $table->string('od_periphery', 50)->nullable();
            $table->string('os_periphery', 50)->nullable();
            $table->string('gonio_od_top', 10)->nullable();
            $table->string('gonio_od_right', 10)->nullable();
            $table->string('gonio_od_bottom', 10)->nullable();
            $table->string('gonio_od_left', 10)->nullable();
            $table->string('gonio_od', 10)->nullable();
            $table->string('gonio_os_top', 10)->nullable();
            $table->string('gonio_os_right', 10)->nullable();
            $table->string('gonio_os_bottom', 10)->nullable();
            $table->string('gonio_os_left', 10)->nullable();
            $table->string('gonio_os', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_medical_records', function (Blueprint $table) {
            //$table->dropColumn('vision_od_img3', 'vision_os_img3');
        });
    }
};
