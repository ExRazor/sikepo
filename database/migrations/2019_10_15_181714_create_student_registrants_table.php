<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRegistrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_registrants', function (Blueprint $table) {
            $table->char('nisn',10)->primary();
            $table->unsignedBigInteger('id_ta');
            $table->string('nama');
            $table->enum('jk',['Laki-Laki','Perempuan']);
            $table->string('agama');
            $table->string('tpt_lhr');
            $table->date('tgl_lhr');
            $table->text('alamat');
            $table->string('no_telp');
            $table->string('email');
            $table->string('asal_sekolah');
            $table->enum('jalur_masuk',['SNMPTN','SBMPTN','Mandiri','Lain-Lain']);
            $table->char('kd_prodi',5);
            $table->enum('status_lulus',['Lulus','Tidak Lulus']);
            $table->timestamps();

            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('student_registrants');
    }
}
