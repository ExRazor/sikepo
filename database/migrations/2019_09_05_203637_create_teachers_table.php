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
            $table->bigIncrements('id');
            $table->char('nidn',10)->unique();
            $table->char('nip',18)->unique()->nullable();
            $table->char('kd_prodi',5);
            $table->string('nama');
            $table->string('jk');
            $table->string('agama')->nullable();
            $table->string('tpt_lhr')->nullable();
            $table->date('tgl_lhr')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('pend_terakhir_jenjang')->nullable();
            $table->string('pend_terakhir_jurusan')->nullable();
            $table->text('bidang_ahli')->nullable();
            $table->enum('sesuai_bidang_ps',['Ya','Tidak'])->nullable();
            $table->string('ikatan_kerja');
            $table->string('jabatan_akademik');
            $table->string('sertifikat_pendidik')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('teachers');
    }
}
