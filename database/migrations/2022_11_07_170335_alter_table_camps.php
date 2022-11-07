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
        Schema::table('camps', function (Blueprint $table) {
            $table->text('address')->nullable()->after('gender');
            $table->String('phone_number', 10)->nullable()->after('address');
            $table->text('notes')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
            $table->dropColumn('notes');
        });
    }
};
