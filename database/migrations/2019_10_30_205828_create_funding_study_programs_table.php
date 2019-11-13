<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundingStudyProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funding_study_programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd_dana');
            $table->char('kd_prodi',5);
            $table->unsignedInteger('id_ta');
            $table->unsignedInteger('id_kategori');
            $table->integer('nominal')->default(0);
            $table->timestamps();

            $table->unique(['kd_prodi','id_ta','id_kategori']);
            $table->foreign('kd_prodi')->references('kd_prodi')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('funding_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funding_study_programs');
    }
}
