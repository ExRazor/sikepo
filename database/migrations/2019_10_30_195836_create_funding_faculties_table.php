<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundingFacultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funding_faculties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd_dana');
            $table->unsignedInteger('id_fakultas');
            $table->unsignedInteger('id_ta');
            $table->unsignedInteger('id_kategori');
            $table->integer('nominal')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->unique(['id_fakultas','id_ta','id_kategori']);
            $table->foreign('id_fakultas')->references('id')->on('faculties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('funding_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funding_faculties');
    }
}
