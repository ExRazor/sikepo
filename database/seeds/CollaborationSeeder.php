<?php

use Illuminate\Database\Seeder;
use App\StudyProgram;
use App\AcademicYear;

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
                // 'kd_prodi' => StudyProgram::where('kd_jurusan',setting('app_department_id'))->inRandomOrder()->first()->kd_prodi,
                'kd_prodi' => 57201,
                'id_ta' => AcademicYear::all()->random()->id,
                'jenis' => 'Pengabdian',
                'nama_lembaga' => 'RRI',
                'tingkat' => 'internasional',
                'judul_kegiatan' => 'Kerja Praktek',
                'manfaat_kegiatan' => 'Pengalaman kerja',
                'waktu' => '2018/12/15',
                'durasi' => '45 hari',
                'bukti_nama' => 'MoU',
                'bukti_file' => 'bukti-1.pdf',
                'created_at' => now()
            ],
            [
                // 'kd_prodi' => StudyProgram::where('kd_jurusan',setting('app_department_id'))->inRandomOrder()->first()->kd_prodi,
                'kd_prodi' => 57201,
                'id_ta' => AcademicYear::all()->random()->id,
                'jenis' => 'Pendidikan',
                'nama_lembaga' => 'Digital Printing',
                'tingkat' => 'nasional',
                'judul_kegiatan' => 'Magang',
                'manfaat_kegiatan' => 'Pengalaman kerja',
                'waktu' => '2018/07/07',
                'durasi' => '45 hari',
                'bukti_nama' => 'Surat Perjanjian Kerjasama',
                'bukti_file' => 'bukti-2.pdf',
                'created_at' => now()
            ],
            [
                // 'kd_prodi' => StudyProgram::where('kd_jurusan',setting('app_department_id'))->inRandomOrder()->first()->kd_prodi,
                'kd_prodi' => 57201,
                'id_ta' => AcademicYear::all()->random()->id,
                'jenis' => 'Pendidikan',
                'nama_lembaga' => 'Digital Printing',
                'tingkat' => 'nasional',
                'judul_kegiatan' => 'Magang',
                'manfaat_kegiatan' => 'Pengalaman kerja',
                'waktu' => '2015/07/07',
                'durasi' => '45 hari',
                'bukti_nama' => 'Surat Perjanjian Kerjasama',
                'bukti_file' => 'bukti-2.pdf',
                'created_at' => now()
            ],
        ]);
    }
}
