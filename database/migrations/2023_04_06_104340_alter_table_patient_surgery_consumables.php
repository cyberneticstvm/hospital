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
        Schema::table('patient_surgery_consumables', function (Blueprint $table) {
            $table->unsignedBigInteger('bill_number')->after('id');
            $table->text('notes')->after('surgery_id')->nullable();
            $table->decimal('total', 7, 2)->after('notes')->default(0.00);
            $table->decimal('discount', 7, 2)->after('total')->default(0.00);
            $table->decimal('total_after_discount', 7, 2)->after('discount')->default(0.00);
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_surgery_consumables', function (Blueprint $table) {
            $table->dropColumn(['bill_number', 'notes', 'total', 'discount', 'total_after_discount']);
        });
    }
};
