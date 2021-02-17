<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityServiceStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_service_students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pengabdian');
            $table->char('nim', 10)->nullable();
            $table->string('nama')->nullable();
            $table->string('asal')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('id_pengabdian')->references('id')->on('community_services')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nim')->references('nim')->on('students')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_service_students');
    }
}
