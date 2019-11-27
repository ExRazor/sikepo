<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nidn',10);
            $table->unsignedInteger('id_ta');
            $table->string('prestasi');
            $table->enum('tingkat_prestasi',['Wilayah','Nasional','Internasional']);
            $table->string('bukti_nama');
            $table->string('bukti_file');
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
        Schema::dropIfExists('teacher_achievements');
    }
}
