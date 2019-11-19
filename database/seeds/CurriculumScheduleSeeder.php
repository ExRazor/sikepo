<?php

use App\AcademicYear;
use App\Curriculum;
use App\StudyProgram;
use Illuminate\Database\Seeder;
use App\CurriculumSchedule;
use App\Teacher;

class CurriculumScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($j = 0; $j < 100; $j++){

            $max_tahun = Curriculum::max('versi');
            $bidang = ['1',''];
            $tahun_akademik = AcademicYear::where('tahun_akademik','>',$max_tahun)->inRandomOrder()->first();

            $teacher = Teacher::all()->random();
            $curriculum = Curriculum::all()->random();

            $sesuai = ($teacher->kd_prodi==$curriculum->kd_prodi ? '1' : null);

            CurriculumSchedule::updateOrCreate(
                [
                    'id_ta'          => $tahun_akademik->id,
                    'nidn'           => $teacher->nidn,
                    'kd_matkul'      => $curriculum->kd_matkul,
                ],
                [
                    'sesuai_prodi'   => $sesuai,
                    'sesuai_bidang'  => $bidang[array_rand($bidang)],
                    'created_at'     => now()
                ]
            );

        }
    }
}
