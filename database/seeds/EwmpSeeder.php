<?php

use Illuminate\Database\Seeder;
use App\Teacher;
use App\AcademicYear;
use App\Ewmp;
use App\CurriculumSchedule;
use App\Research;
use App\CommunityService;
class EwmpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 100; $i++){

            $id_ta   = AcademicYear::all()->random()->id;
            $nidn    = Teacher::inRandomOrder()->where('ikatan_kerja','Dosen Tetap PS')->first()->nidn;

            $curriculum_ps  = CurriculumSchedule::where('nidn',$nidn)->where('id_ta',$id_ta)->whereNotNull('sesuai_prodi')->get();
            $curriculum_pt  = CurriculumSchedule::where('nidn',$nidn)->where('id_ta',$id_ta)->whereNull('sesuai_prodi')->get();

            $penelitian     = Research::where('id_ta',$id_ta)
                                        ->with([
                                            'researchTeacher' => function($q1) use ($nidn) {
                                                $q1->where('nidn',$nidn);
                                            }
                                        ])
                                        ->whereHas(
                                            'researchTeacher', function($q1) use ($nidn) {
                                                $q1->where('nidn',$nidn);
                                            }
                                        )
                                        ->get();

            $pengabdian     = CommunityService::where('id_ta',$id_ta)
                                        ->with([
                                            'serviceTeacher' => function($q1) use ($nidn) {
                                                $q1->where('nidn',$nidn);
                                            }
                                        ])
                                        ->whereHas(
                                            'serviceTeacher', function($q1) use ($nidn) {
                                                $q1->where('nidn',$nidn);
                                            }
                                        )
                                        ->get();

            $count_ps = array(0);
            $count_pt = array(0);
            $count_penelitian = array(0);
            $count_pengabdian = array(0);

            foreach($curriculum_ps as $ps) {
                $count_ps[] = $ps->curriculum->sks_teori + $ps->curriculum->sks_seminar + $ps->curriculum->sks_praktikum;
            }

            foreach($curriculum_pt as $pt) {
                $count_pt[] = $pt->curriculum->sks_teori + $pt->curriculum->sks_seminar + $pt->curriculum->sks_praktikum;
            }

            foreach($penelitian as $p) {
                $count_penelitian[] = $p->researchTeacher[0]->sks;
            }

            foreach($pengabdian as $p) {
                $count_pengabdian[] = $p->serviceTeacher[0]->sks;
            }

            Ewmp::updateOrCreate(
                [
                    'nidn'                  => $nidn,
                    'id_ta'                 => $id_ta,
                ],
                [
                    'ps_intra'              => array_sum($count_ps),
                    'ps_lain'               => array_sum($count_pt),
                    'ps_luar'               => rand(0, 85)/10,
                    'penelitian'            => array_sum($count_penelitian),
                    'pkm'                   => array_sum($count_pengabdian),
                    'tugas_tambahan'        => rand(0.0, 5.5)/10,
                    'created_at'            => now()
                ]
            );
        }
    }
}
