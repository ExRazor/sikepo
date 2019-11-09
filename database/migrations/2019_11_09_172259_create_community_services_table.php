<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_services', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nidn',9);
            $table->string('tema_pengabdian');
            $table->string('judul_pengabdian');
            $table->string('tahun_pengabdian');
            $table->string('sumber_biaya');
            $table->string('sumber_biaya_nama')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('community_services');
    }
}
