<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEwmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ewmps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_ta');
            $table->char('nidn',10);
            $table->float('ps_intra')->default(0);
            $table->float('ps_lain')->default(0);
            $table->float('ps_luar')->default(0);
            $table->float('penelitian')->default(0);
            $table->float('pkm')->default(0);
            $table->float('tugas_tambahan')->default(0);
            $table->float('total_sks')->default(0);
            $table->float('rata_sks')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->unique(['id_ta','nidn']);
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
        Schema::dropIfExists('ewmps');
    }
}
