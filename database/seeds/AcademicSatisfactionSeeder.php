<?php

use Illuminate\Database\Seeder;
use App\AcademicYear;
use App\StudyProgram;
use App\SatisfactionCategory;

class AcademicSatisfactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::all();
        $category     = SatisfactionCategory::where('jenis','Pendidikan')->get();

        foreach($academicYear as $ay) {
            foreach($studyProgram as $sp) {
                foreach($category as $cat) {

                    $sb = rand(0,100);
                    $b  = rand(0,100-$sb);
                    $c  = rand(0,100-$sb-$b);
                    $k  = 100-$sb-$b-$c;

                    DB::table('academic_satisfactions')->insert([
                        'id_ta'          => $ay->id,
                        'kd_prodi'       => $sp->kd_prodi,
                        'id_kategori'    => $cat->id,
                        'sangat_baik'    => $sb,
                        'baik'           => $b,
                        'cukup'          => $c,
                        'kurang'         => $k,
                        'tindak_lanjut'  => '',
                        'created_at'     => now()
                    ]);
                }
            }
        }
    }
}
