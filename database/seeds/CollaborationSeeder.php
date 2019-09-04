<?php

use Illuminate\Database\Seeder;

class CollaborationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collaborations')->insert([
            [
                'kd_prodi' => 53141,
                'id_ta' => 1,
                'nama_lembaga' => 'RRI',
                'tingkat' => 'lokal',
                'judul_kegiatan' => 'Kerja Praktek',
                'manfaat_kegiatan' => 'Pengalaman kerja',
                'waktu' => '2018/12/15',
                'durasi' => '45 hari',
                'bukti' => 'bukti-1.pdf'
            ],
            [
                'kd_prodi' => 53242,
                'id_ta' => 1,
                'nama_lembaga' => 'DIgital Printing',
                'tingkat' => 'lokal',
                'judul_kegiatan' => 'Magang',
                'manfaat_kegiatan' => 'Pengalaman kerja',
                'waktu' => '2018/07/07',
                'durasi' => '45 hari',
                'bukti' => 'bukti-2.pdf'
            ],
        ]);
    }
}
