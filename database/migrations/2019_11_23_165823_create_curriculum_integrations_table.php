<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculumIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_integrations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_penelitian')->nullable();
            $table->unsignedInteger('id_pengabdian')->nullable();
            $table->string('kegiatan');
            $table->char('nidn',10);
            $table->string('kd_matkul',12);
            $table->string('bentuk_integrasi');
            $table->timestamps();

            $table->foreign('id_penelitian')->references('id')->on('researches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_pengabdian')->references('id')->on('community_services')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('curriculum_integrations');
    }
}
