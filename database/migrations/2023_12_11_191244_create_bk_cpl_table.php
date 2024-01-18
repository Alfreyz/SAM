<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkCplTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bk_cpl', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bk', 4);
            $table->string('kode_cpl', 4);
            $table->foreign('kode_bk')->references('kode_bk')->on('bk');
            $table->foreign('kode_cpl')->references('kode_cpl')->on('cpl');
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
        Schema::dropIfExists('bk_cpl');
    }
}
