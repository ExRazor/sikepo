<?php

use Illuminate\Database\Seeder;
use App\AcademicYear;
use App\StudyProgram;
use App\SatisfactionCategory;

class AlumnusSatisfactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();
        $academicYear = AcademicYear::where('semester','Ganjil')->get();
        $category     = SatisfactionCategory::where('jenis','Alumni')->get();

        foreach($academicYear as $ay) {
            foreach($studyProgram as $sp) {
                foreach($category as $cat) {

                    $sb = rand(0,100);
                    $b  = rand(0,100-$sb);
                    $c  = rand(0,100-$sb-$b);
                    $k  = 100-$sb-$b-$c;

                    DB::table('academic_satisfactions')->insert([
                        'kd_kepuasan'    => $ay->id.'_'.$sp->kd_prodi,
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
