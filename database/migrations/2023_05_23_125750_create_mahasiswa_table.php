<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->unsignedBigInteger('nidn');
            $table->foreign('nidn')->references('id')->on('dosen')->onDelete('cascade');
            $table->unsignedBigInteger('kode_matakuliah');
            $table->foreign('kode_matakuliah')->references('id')->on('matakuliah')->onDelete('cascade');
            $table->unsignedBigInteger('bahan_kajian');
            $table->foreign('bahan_kajian')->references('id')->on('matakuliah')->onDelete('cascade');
            $table->unsignedBigInteger('cpl');
            $table->foreign('cpl')->references('id')->on('matakuliah')->onDelete('cascade');
            $table->string('nilai');
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
        Schema::dropIfExists('mahasiswa');
    }
}
