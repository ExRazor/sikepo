<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_penelitian');
            $table->char('nim',9);
            $table->string('nama');
            $table->char('kd_prodi',5);
            $table->timestamps();

            $table->foreign('id_penelitian')->references('id')->on('researches')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('research_students');
    }
}
