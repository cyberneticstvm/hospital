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

            $table->text('medicine_list')->default('NULL')->change();
            $table->date('review_date')->nullable()->change();
            $table->String('symptoms')->default('NULL')->change();
            $table->text('patient_complaints')->nullable()->change();
            $table->String('diagnosis')->default('NULL')->change();
            $table->text('doctor_findings')->nullable()->change();
            $table->text('doctor_recommondations')->nullable()->change();

            //$table->boolean('is_admission')->default('0');
            //$table->boolean('is_surgery')->default('0');
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
            //
        });
    }
};
