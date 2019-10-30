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

        foreach($academicYear as $ay) {
            foreach($category as $c) {

                FundingStudyProgram::updateOrCreate(
                    [
                        'kd_prodi'      => StudyProgram::where('kd_jurusan',setting('app_department_id'))->inRandomOrder()->first()->kd_prodi,
                        'id_ta'         => $ay->id,
                    ],
                    [
                        'id_kategori'   => $c->id,
                        'nominal'       => rand(10000000, 50000000),
                        'created_at'    => now()
                    ]
                );
            }
        }
    }
}
