<?php

use Illuminate\Database\Seeder;
use App\StudyProgram;
use App\AcademicYear;
use App\StudentQuota;

class StudentQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studyProgram = StudyProgram::all();
        $academicYear = AcademicYear::where('semester','ganjil')->where('tahun_akademik','>=','2013')->get();

        foreach($studyProgram as $sp) {
            foreach($academicYear as $ay) {

                $dayatampung = rand(300,600);
                $calon_lulus = rand(300,600);
                while($calon_lulus > $dayatampung) {
                    $calon_lulus = rand(300,600);
                }

                StudentQuota::updateOrCreate(
                    [
                        'kd_prodi'              => $sp->kd_prodi,
                        'id_ta'                 => $ay->id,
                    ],
                    [
                        'daya_tampung'          => $dayatampung,
                        'calon_pendaftar'       => rand(100,900),
                        'calon_lulus'           => $calon_lulus,
                        'created_at'            => now()
                    ]
                );
            }
        }

    }
}
