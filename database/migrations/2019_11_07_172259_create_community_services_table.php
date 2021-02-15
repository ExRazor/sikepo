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
            $table->unsignedInteger('id_ta');
            $table->string('tema_pengabdian');
            $table->string('judul_pengabdian');
            $table->string('sks_pengabdian');
            $table->char('sesuai_prodi', 1)->nullable();
            $table->string('sumber_biaya');
            $table->string('sumber_biaya_nama')->nullable();
            $table->integer('jumlah_biaya');
            $table->string('bukti_fisik')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

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
        Schema::dropIfExists('community_services');
    }
}
