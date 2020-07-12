<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_publications', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',10);
            $table->unsignedInteger('jenis_publikasi');
            $table->string('judul');
            $table->string('penerbit');
            $table->char('tahun',4);
            $table->char('sesuai_prodi',1)->nullable();
            $table->integer('sitasi')->nullable();
            $table->string('jurnal')->nullable();
            $table->string('akreditasi')->nullable();
            $table->string('tautan')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('nim')->references('nim')->on('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jenis_publikasi')->references('id')->on('publication_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_publications');
    }
}
