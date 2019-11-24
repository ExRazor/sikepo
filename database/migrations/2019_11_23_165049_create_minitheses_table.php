<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinithesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minitheses', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',10);
            $table->string('judul');
            $table->char('pembimbing_utama',10);
            $table->char('pembimbing_akademik',10)->nullable();
            $table->unsignedInteger('id_ta');
            $table->timestamps();

            $table->foreign('pembimbing_utama')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pembimbing_akademik')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nim')->references('nim')->on('students')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('minitheses');
    }
}
