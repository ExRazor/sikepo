<?php

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Ewmp;
use App\Models\CurriculumSchedule;
use App\Models\Research;
use App\Models\CommunityService;
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

            $ewmp_ps_intra      = array_sum($count_ps);
            $ewmp_ps_lain       = array_sum($count_pt);
            $ewmp_ps_luar       = rand(0, 85)/10;
            $ewmp_penelitian    = array_sum($count_penelitian);
            $ewmp_pkm           = array_sum($count_pengabdian);
            $ewmp_tambahan      = rand(0.0, 5.5)/10;

            $ewmp_total_sks  = $ewmp_ps_intra+$ewmp_ps_lain+$ewmp_ps_luar+$ewmp_penelitian+$ewmp_pkm+$ewmp_tambahan;
            $ewmp_rata_sks   = $ewmp_total_sks/6;

            Ewmp::updateOrCreate(
                [
                    'nidn'              => $nidn,
                    'id_ta'             => $id_ta,
                ],
                [
                    'ps_intra'          => $ewmp_ps_intra,
                    'ps_lain'           => $ewmp_ps_lain,
                    'ps_luar'           => $ewmp_ps_luar,
                    'penelitian'        => $ewmp_penelitian,
                    'pkm'               => $ewmp_pkm,
                    'tugas_tambahan'    => $ewmp_tambahan,
                    'total_sks'         => $ewmp_total_sks,
                    'rata_sks'          => $ewmp_rata_sks,
                    'created_at'        => now()
                ]
            );
        }
    }
}
