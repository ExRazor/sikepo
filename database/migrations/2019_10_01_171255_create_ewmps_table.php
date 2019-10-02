<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEwmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ewmps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('nidn',9);
            $table->unsignedBigInteger('id_ta');
            $table->integer('ps_intra');
            $table->integer('ps_lain');
            $table->integer('ps_luar');
            $table->integer('penelitian');
            $table->integer('pkm');
            $table->integer('tugas_tambahan');
            $table->timestamps();

            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ewmps');
    }
}
