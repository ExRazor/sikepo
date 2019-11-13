<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutputActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('output_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_kategori');
            $table->unsignedInteger('id_penelitian')->nullable();
            $table->unsignedInteger('id_pengabdian')->nullable();
            $table->string('kegiatan');
            $table->string('judul_luaran');
            $table->char('tahun_luaran',4);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')->references('id')->on('output_activity_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_penelitian')->references('id')->on('researches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_pengabdian')->references('id')->on('community_services')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('output_activities');
    }
}
