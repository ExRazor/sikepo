<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_publications', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nidn',12);
            $table->unsignedInteger('jenis_publikasi');
            $table->string('judul');
            $table->string('penerbit');
            $table->unsignedInteger('id_ta');
            $table->char('sesuai_prodi',1)->nullable();
            $table->integer('sitasi')->nullable();
            $table->string('jurnal')->nullable();
            $table->string('akreditasi')->nullable();
            $table->string('tautan')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jenis_publikasi')->references('id')->on('publication_categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('teacher_publications');
    }
}
