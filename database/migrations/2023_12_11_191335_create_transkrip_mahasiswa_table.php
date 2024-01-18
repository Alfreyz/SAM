<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranskripMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transkrip_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 12);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->string('kode_matakuliah', 6);
            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliah')->onDelete('cascade');
            $table->string('nilai', 2);
            $table->string('bobot', 5);
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
        Schema::dropIfExists('transkrip_mahasiswa');
    }
}
