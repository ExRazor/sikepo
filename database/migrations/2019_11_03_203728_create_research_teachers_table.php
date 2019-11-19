<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_penelitian');
            $table->string('status');
            $table->float('sks');
            $table->char('nidn',10);

            $table->foreign('id_penelitian')->references('id')->on('researches')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('research_teachers');
    }
}
