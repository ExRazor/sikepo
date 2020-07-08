<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('nim',10)->unique();
            $table->string('nama');
            $table->string('tpt_lhr')->nullable();
            $table->date('tgl_lhr')->nullable();
            $table->string('jk');
            $table->string('agama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kewarganegaraan');
            $table->char('kd_prodi',5);
            $table->string('kelas')->nullable();
            $table->string('tipe')->nullable();
            $table->string('program')->nullable();
            $table->string('seleksi_jenis')->nullable();
            $table->string('seleksi_jalur')->nullable();
            $table->string('status_masuk')->nullable();
            $table->char('angkatan',4)->nullable();
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
        Schema::dropIfExists('students');
    }
}
