<?php

use Illuminate\Database\Seeder;

use App\Student;
use App\AcademicYear;

class StudentAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kegiatan = [
                    'Lomba Debat Bahasa Inggris',
                    'Lomba Teknologi Future Digital',
                    'lomba SEMANTIK',
                    'Kompetisi NextDev'
                ];

        $tingkat = [
                    'Wilayah',
                    'Nasional',
                    'Internasional',
                ];

        $prestasi = [
                    'Menjadi Juara I',
                    'Menjadi Juara II',
                    'Menjadi Juara III',
                    'Menjadi Runner-Up'
                ];

        $jenis = [
                    'Akademik',
                    'Non Akademik',
                ];

        for($i = 0; $i < 100; $i++){
            $nim = Student::whereHas(
                                'studyProgram', function($q) {
                                    $q->where('kd_jurusan',setting('app_department_id'));
                                }
                            )
                            ->inRandomOrder()
                            ->first()
                            ->nim;

            for($j = 0; $j < 5; $j++){
                DB::table('student_achievements')->insert([
                    'nim'               => $nim,
                    'id_ta'             => AcademicYear::all()->random()->id,
                    'kegiatan_nama'     => $kegiatan[array_rand($kegiatan)],
                    'kegiatan_tingkat'  => $tingkat[array_rand($tingkat)],
                    'prestasi'          => $prestasi[array_rand($prestasi)],
                    'prestasi_jenis'    => $jenis[array_rand($jenis)],
                    'created_at'        => now()
                ]);
            }
        }
    }
}
