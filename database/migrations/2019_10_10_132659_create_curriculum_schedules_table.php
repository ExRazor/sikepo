<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculumSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_ta');
            $table->char('nidn',12);
            $table->string('kd_matkul',12);
            $table->char('sesuai_prodi',1)->nullable();
            $table->char('sesuai_bidang',1)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->unique(['id_ta','nidn','kd_matkul']);

            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kd_matkul')->references('kd_matkul')->on('curricula')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_schedules');
    }
}
