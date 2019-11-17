<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_ta');
            $table->char('nidn',10);
            $table->char('kd_matkul',10)->nullable();
            $table->string('nm_matkul')->nullable();
            $table->char('kd_prodi',10)->nullable();
            $table->char('sesuai_prodi',1)->nullable();
            $table->char('sesuai_bidang',1);
            $table->timestamps();

            $table->unique(['id_ta','nidn']);

            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kd_matkul')->references('kd_matkul')->on('curricula')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('teacher_schedules');
    }
}
