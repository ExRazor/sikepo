<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedInteger('id_ta');
            $table->char('nidn',12);
            $table->date('periode');
            $table->string('jabatan');
            $table->char('kd_prodi',5)->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // $table->foreign('id_ta')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kd_prodi')->references('kd_prodi')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_statuses');
    }
}
