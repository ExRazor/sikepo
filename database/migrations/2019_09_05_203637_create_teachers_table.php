<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->char('nidn',9)->primary();
            $table->string('nama');
            $table->enum('jk',['Laki-Laki','Perempuan']);
            $table->string('agama');
            $table->string('tpt_lhr');
            $table->date('tgl_lhr');
            $table->text('alamat');
            $table->string('no_telp');
            $table->string('email');
            $table->string('pend_terakhir');
            $table->string('bidang_ahli');
            $table->char('dosen_ps',5);
            $table->enum('status_pengajar',['DT','DTT']);
            $table->string('jabatan_akademik');
            $table->string('sertifikat_pendidik');
            $table->enum('sesuai_bidang_ps',['Ya','Tidak']);
            $table->timestamps();

            $table->foreign('dosen_ps')->references('kd_prodi')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
