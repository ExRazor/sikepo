<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentForeignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_foreigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('nim',10);
            $table->string('asal_negara');
            $table->string('durasi');
            $table->timestamps();

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
        Schema::dropIfExists('student_foreigns');
    }
}
