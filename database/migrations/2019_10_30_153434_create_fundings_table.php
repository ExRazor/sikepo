<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fundings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_ta');
            $table->unsignedBigInteger('id_parent')->nullable();
            $table->string('jenis_biaya')->nullable();
            $table->string('penggunaan');
            $table->string('deskripsi')->nullable();
            $table->string('alokasi_upps')->nullable();
            $table->string('alokasi_prodi')->nullable();
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
        Schema::dropIfExists('fundings');
    }
}
