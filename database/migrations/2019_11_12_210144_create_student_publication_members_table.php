<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPublicationMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_publication_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_publikasi');
            $table->char('nim',10);
            $table->string('nama');
            $table->char('kd_prodi',5);
            $table->timestamps();

            $table->foreign('id_publikasi')->references('id')->on('student_publications')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('student_publication_members');
    }
}
