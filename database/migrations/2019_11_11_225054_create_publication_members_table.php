<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publication_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_publikasi');
            $table->char('id_unik', 12)->nullable();
            $table->string('nama')->nullable();
            $table->string('asal')->nullable();
            $table->string('status');
            $table->boolean('penulis_utama')->nullable()->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('id_publikasi')->references('id')->on('teacher_publications')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publication_members');
    }
}
