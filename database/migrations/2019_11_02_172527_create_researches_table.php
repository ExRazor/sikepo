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
            $table->bigIncrements('id');
            $table->char('nidn',9);
            $table->string('tema_penelitian');
            $table->string('judul_penelitian');
            $table->string('sumber_biaya');
            $table->timestamps();
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
