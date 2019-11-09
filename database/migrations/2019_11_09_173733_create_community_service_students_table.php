<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityServiceStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_service_students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pengabdian');
            $table->char('nim',10);
            $table->string('nama');
            $table->char('kd_prodi',5);
            $table->timestamps();

            $table->foreign('id_pengabdian')->references('id')->on('community_services')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('community_service_students');
    }
}
