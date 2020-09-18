<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherPublicationMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_publication_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_publikasi');
            $table->char('nidn',12);
            $table->string('nama');
            $table->char('kd_prodi',5);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('id_publikasi')->references('id')->on('teacher_publications')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('teacher_publication_members');
    }
}
