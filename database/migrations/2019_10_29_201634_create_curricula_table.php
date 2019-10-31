<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->string('kd_matkul',12)->primary();
            $table->char('kd_prodi',5);
            $table->string('nama');
            $table->integer('semester');
            $table->string('jenis');
            $table->integer('versi');
            $table->integer('sks_teori');
            $table->integer('sks_seminar');
            $table->integer('sks_praktikum');
            $table->string('capaian');
            $table->string('dokumen_nama')->nullable();
            $table->string('dokumen_file')->nullable();

            $table->foreign('kd_prodi')->references('kd_prodi')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curricula');
    }
}
