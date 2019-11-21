<?php
namespace App\Traits;

use App\Ewmp;

trait EwmpTrait
{
    public function updateEwmp($id_ta,$nidn) {

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

        $data = array(
            'schedule_ps'   => array_sum($count_ps),
            'schedule_pt'   => array_sum($count_pt),
            'penelitian'    => array_sum($count_penelitian),
            'pengabdian'    => array_sum($count_pengabdian)
        );

        $ewmp                   = Ewmp::where('nidn',$nidn)->where('id_ta',$id_ta)->first();
        $ewmp->ps_intra         = array_sum($count_ps);
        $ewmp->ps_lain          = array_sum($count_pt);
        $ewmp->penelitian       = array_sum($count_penelitian);
        $ewmp->pkm              = array_sum($count_pengabdian);
        $ewmp->save();

    }
}
