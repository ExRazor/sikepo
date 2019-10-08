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
            $table->bigIncrements('id');
            $table->char('nidn',9);
            $table->string('prestasi');
            $table->enum('tingkat_prestasi',['Wilayah','Nasional','Internasional']);
            $table->date('tanggal');
            $table->string('bukti_pendukung');
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
        Schema::dropIfExists('teacher_achievements');
    }
}
