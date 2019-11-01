<?php

use Illuminate\Database\Seeder;
use App\StudyProgram;
use App\FundingCategory;
use App\AcademicYear;
use App\FundingStudyProgram;

class FundStudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $academicYear = AcademicYear::where('semester','Ganjil')->where('tahun_akademik','>','2013')->get();
        $category     = FundingCategory::all();
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        foreach($studyProgram as $sp) {
            foreach($academicYear as $ay) {
                foreach($category as $c) {
                    if(!$c->children->count()) {
                        $nominal = rand(10000, 50000).'000';
                        FundingStudyProgram::updateOrCreate(
                            [
                                'kd_dana'    => 'pd'.$sp->kd_prodi.'_thn'.$ay->id,
                                'kd_prodi'      => $sp->kd_prodi,
                                'id_ta'         => $ay->id,
                                'id_kategori'   => $c->id,
                            ],
                            [
                                'nominal'       => $nominal,
                                'created_at'    => now()
                            ]
                        );
                    }
                }
            }
        }
    }
}
