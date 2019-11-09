<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->char('kd_prodi',5)->primary();
            $table->char('kd_jurusan',5);
            $table->char('kd_unik',4)->unique();
            $table->string('nama');
            $table->string('singkatan')->nullable();
            $table->string('jenjang');
            $table->string('no_sk')->nullable();
            $table->date('tgl_sk')->nullable();
            $table->string('pejabat_sk')->nullable();
            $table->char('thn_menerima',4)->nullable();
            $table->char('nip_kaprodi',18)->nullable();
            $table->string('nm_kaprodi',50)->nullable();
            $table->timestamps();

            $table->foreign('kd_jurusan')->references('kd_jurusan')->on('departments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_programs');
    }
}
