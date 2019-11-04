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
            $table->char('nim',9)->primary();
            $table->string('nama');
            $table->string('tpt_lhr')->nulllable();
            $table->date('tgl_lhr');
            $table->string('jk');
            $table->string('agama');
            $table->string('alamat');
            $table->string('kewarganegaraan');
            $table->char('kd_prodi',5);
            $table->string('kelas');
            $table->string('tipe');
            $table->string('program');
            $table->string('seleksi_jenis');
            $table->string('seleksi_jalur');
            $table->string('status_masuk');
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
