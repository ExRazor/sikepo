<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicSatisfactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_satisfactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd_kepuasan');
            $table->unsignedInteger('id_ta');
            $table->char('kd_prodi',5);
            $table->unsignedInteger('id_kategori');
            $table->integer('sangat_baik')->nullable();
            $table->integer('baik')->nullable();
            $table->integer('cukup')->nullable();
            $table->integer('kurang')->nullable();
            $table->string('tindak_lanjut')->nullable();
            $table->timestamps();

            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kd_prodi')->references('kd_prodi')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('satisfaction_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_satisfactions');
    }
}
