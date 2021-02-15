<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->char('nidn', 12)->primary();
            $table->char('nip', 18)->unique()->nullable();
            $table->string('nama');
            $table->string('jk');
            $table->string('agama')->nullable();
            $table->string('tpt_lhr')->nullable();
            $table->date('tgl_lhr')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('pend_terakhir_jenjang')->nullable();
            $table->string('pend_terakhir_jurusan')->nullable();
            $table->text('bidang_ahli')->nullable();
            $table->enum('sesuai_bidang_ps', ['Ya', 'Tidak'])->nullable();
            $table->string('status_kerja');
            $table->string('jabatan_akademik');
            $table->string('sertifikat_pendidik')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
