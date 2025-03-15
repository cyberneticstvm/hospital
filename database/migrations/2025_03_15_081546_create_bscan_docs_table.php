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
        Schema::create('bscan_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bscan_id');
            $table->string('doc_type')->nullable();
            $table->string('doc_url')->nullable();
            $table->foreign('bscan_id')->references('id')->on('bscans')->onDelete('cascade');
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
        Schema::dropIfExists('bscan_docs');
    }
};
