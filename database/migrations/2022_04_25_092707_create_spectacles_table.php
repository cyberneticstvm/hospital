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
        Schema::create('spectacles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->String('re_dist_sph')->nullable();
            $table->String('re_dist_cyl')->nullable();
            $table->String('re_dist_axis')->nullable();
            $table->String('re_dist_va')->nullable();
            $table->String('re_dist_prism')->nullable();
            $table->String('re_dist_add')->nullable();
            $table->String('re_int_sph')->nullable();
            $table->String('re_int_cyl')->nullable();
            $table->String('re_int_axis')->nullable();
            $table->String('re_int_va')->nullable();
            $table->String('re_int_prism')->nullable();
            $table->String('re_int_add')->nullable();
            $table->String('re_near_sph')->nullable();
            $table->String('re_near_cyl')->nullable();
            $table->String('re_near_axis')->nullable();
            $table->String('re_near_va')->nullable();
            $table->String('re_near_prism')->nullable();
            $table->String('re_near_add')->nullable();
            $table->String('le_dist_sph')->nullable();
            $table->String('le_dist_cyl')->nullable();
            $table->String('le_dist_axis')->nullable();
            $table->String('le_dist_va')->nullable();
            $table->String('le_dist_prism')->nullable();
            $table->String('le_dist_add')->nullable();
            $table->String('le_int_sph')->nullable();
            $table->String('le_int_cyl')->nullable();
            $table->String('le_int_axis')->nullable();
            $table->String('le_int_va')->nullable();
            $table->String('le_int_prism')->nullable();
            $table->String('le_int_add')->nullable();
            $table->String('le_near_sph')->nullable();
            $table->String('le_near_cyl')->nullable();
            $table->String('le_near_axis')->nullable();
            $table->String('le_near_va')->nullable();
            $table->String('le_near_prism')->nullable();
            $table->String('le_near_add')->nullable();
            $table->String('re_base_curve')->nullable();
            $table->String('re_dia')->nullable();
            $table->String('re_sph')->nullable();
            $table->String('re_cyl')->nullable();
            $table->String('re_axis')->nullable();
            $table->String('le_base_curve')->nullable();
            $table->String('le_dia')->nullable();
            $table->String('le_sph')->nullable();
            $table->String('le_cyl')->nullable();
            $table->String('le_axis')->nullable();
            $table->String('vd')->nullable();
            $table->String('ipd')->nullable();
            $table->String('npd')->nullable();
            $table->String('rpd')->nullable();
            $table->String('lpd')->nullable();
            $table->String('vbr')->nullable();
            $table->String('vbl')->nullable();
            $table->String('re_iop')->nullable();
            $table->String('le_iop')->nullable();
            $table->text('remarks')->nullable();
            $table->text('advice')->nullable();
            $table->date('review_date')->nullable();
            $table->string('glasses_prescribed', 5)->nullable();
            $table->unsignedBigInteger('created_by')->references('id')->on('users');
            $table->foreign('medical_record_id')->references('id')->on('patient_medical_records')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spectacles');
    }
};
