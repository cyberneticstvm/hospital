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
        Schema::create('patient_acknowledgement_procedures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_acknowledgement_id');
            $table->unsignedBigInteger('procedure_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_acknowledgement_procedures');
    }
};
