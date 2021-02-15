<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaborationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('kd_prodi', 5);
            $table->unsignedInteger('id_ta');
            $table->string('jenis');
            $table->string('nama_lembaga');
            $table->enum('tingkat', ['Internasional', 'Nasional', 'Lokal']);
            $table->string('judul_kegiatan');
            $table->text('manfaat_kegiatan');
            $table->date('waktu');
            $table->string('durasi');
            $table->text('tindak_lanjut')->nullable();
            $table->string('bukti_nama');
            $table->string('bukti_file');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('kd_prodi')->references('kd_prodi')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collaborations');
    }
}
