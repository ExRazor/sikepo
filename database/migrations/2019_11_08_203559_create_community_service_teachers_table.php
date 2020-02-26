<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityServiceTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_service_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_pengabdian');
            $table->string('status');
            $table->float('sks');
            $table->char('nidn',10);
            $table->string('nama_lain')->nullable();
            $table->string('asal_lain')->nullable();

            $table->foreign('id_pengabdian')->references('id')->on('community_services')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_service_teachers');
    }
}
