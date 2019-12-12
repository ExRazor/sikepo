<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_ta');
            $table->string('tema_penelitian');
            $table->string('judul_penelitian');
            $table->integer('sks_penelitian');
            $table->char('sesuai_prodi',1)->nullable();
            $table->string('sumber_biaya');
            $table->string('sumber_biaya_nama')->nullable();
            $table->integer('jumlah_biaya');
            $table->timestamps();

            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('researches');
    }
}
