<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherOutputActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_output_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kegiatan');
            $table->string('nm_kegiatan')->nullable();
            $table->string('nidn');
            $table->unsignedInteger('id_kategori');
            $table->string('judul_luaran');
            $table->char('thn_luaran',4);
            $table->string('jenis_luaran');
            $table->string('nama_karya');
            $table->string('jenis_karya')->nullable();
            $table->string('pencipta_karya')->nullable();
            $table->string('issn')->nullable();
            $table->string('no_paten')->nullable();
            $table->date('tgl_sah')->nullable();
            $table->string('no_permohonan')->nullable();
            $table->date('tgl_permohonan')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('url')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('file_karya')->nullable();
            $table->timestamps();

            $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('output_activity_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_output_activities');
    }
}
