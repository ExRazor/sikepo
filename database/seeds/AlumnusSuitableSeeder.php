<?php

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\StudyProgram;
use App\Models\StudentStatus;

class AlumnusSuitableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $academicYear = AcademicYear::groupBy('tahun_akademik')->orderBy('tahun_akademik','desc')->get('tahun_akademik');
        $studyProgram = StudyProgram::where('kd_jurusan',setting('app_department_id'))->get();

        foreach($academicYear as $ay) {
            foreach($studyProgram as $sp) {
                $lulusan = StudentStatus::whereHas(
                                            'student', function($q) use($sp) {
                                                $q->where('kd_prodi',$sp->kd_prodi);
                                            }
                                        )
                                        ->whereHas(
                                            'academicYear', function($q) use($ay) {
                                                $q->where('tahun_akademik',$ay->tahun_akademik);
                                            }
                                        )
                                        ->count();
                $terlacak   = rand(0,$lulusan);
                $kriteria_1 = rand(0,$terlacak);
                $kriteria_2 = rand(0,$terlacak-$kriteria_1);
                $kriteria_3 = $terlacak-($kriteria_1+$kriteria_2);

                DB::table('alumnus_suitables')->insert([
                    'kd_prodi'          => $sp->kd_prodi,
                    'tahun_lulus'       => $ay->tahun_akademik,
                    'jumlah_lulusan'    => $lulusan,
                    'lulusan_terlacak'  => $terlacak,
                    'sesuai_rendah'     => $kriteria_1,
                    'sesuai_sedang'     => $kriteria_2,
                    'sesuai_tinggi'     => $kriteria_3,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
