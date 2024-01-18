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
            $table->string('nim', 12)->unique();
            $table->string('nama')->nullable();
            $table->string('password');
            $table->string('nidn', 12);
            $table->string('angkatan', 4);
            $table->string('status', 20);
            $table->timestamps();
            $table->foreign('nidn')->references('nidn')->on('dosen');
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
