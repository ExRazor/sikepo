<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnusWorkplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnus_workplaces', function (Blueprint $table) {
            $table->increments('id');
            $table->char('kd_prodi',5);
            $table->char('tahun_lulus',4);
            $table->integer('jumlah_lulusan');
            $table->integer('lulusan_bekerja');
            $table->integer('kerja_lokal');
            $table->integer('kerja_nasional');
            $table->integer('kerja_internasional');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

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
        Schema::dropIfExists('alumnus_workplaces');
    }
}
