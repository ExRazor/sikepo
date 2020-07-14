<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Research;
use App\Models\ResearchTeacher;
use App\Models\CommunityService;
use App\Models\CommunityServiceTeacher;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty = Faculty::all();
        $setting = Setting::all();

        foreach($setting as $s) {
            $data[$s->name] = $s->value;
        }

        return view('setting.general',compact(['faculty','data']));
    }

    public function update(Request $request)
    {
        $kd_jurusan = decrypt($request->app_department_id);

        $jurusan = Department::with('faculty')->where('kd_jurusan',$kd_jurusan)->first();
        $data = array(
            'app_name'            => $request->app_name,
            'app_short'           => $request->app_short,
            'app_description'     => $request->app_description,
            'app_department_id'   => $kd_jurusan,
            'app_department_name' => $jurusan->nama,
            'app_faculty_id'      => $jurusan->faculty->id,
            'app_faculty_name'    => $jurusan->faculty->nama,
            'research_ratio_chief'      => $request->research_ratio_chief,
            'research_ratio_members'    => $request->research_ratio_members,
            'service_ratio_chief'       => $request->service_ratio_chief,
            'service_ratio_members'     => $request->service_ratio_members,
        );

        foreach($data as $key => $value) {
            Setting::updateOrCreate(
                [
                    'name'         => $key,
                ],
                [
                    'value'        => $value,
                ]
            );
        }

        $this->update_sks();

        return redirect()->route('setting.general')->with('flash.message', 'Setelan berhasil diubah!')->with('flash.class', 'success');
    }

    public function update_sks()
    {
        $research = Research::all();
        $service  = CommunityService::all();

        if($research->count()) {
            foreach($research as $rs) {
                $members = ResearchTeacher::where('id_penelitian',$rs->id)->get();

                $sks_ketua      = floatval($rs->sks_penelitian)*setting('research_ratio_chief')/100;
                $sks_anggota    = floatval($rs->sks_penelitian)*setting('research_ratio_members')/100;
                foreach($members as $m) {
                    if($m->status=='Ketua') {
                        $m->sks = $sks_ketua;
                    } else {
                        $m->sks = $sks_anggota;
                    }
                    $m->save();
                }
            }
        }

        if($service->count()) {
            foreach($service as $s) {
                $members = CommunityServiceTeacher::where('id_pengabdian',$s->id)->get();

                $sks_ketua      = floatval($s->sks_pengabdian)*setting('service_ratio_chief')/100;
                $sks_anggota    = floatval($s->sks_pengabdian)*setting('service_ratio_members')/100;
                foreach($members as $m) {
                    if($m->status=='Ketua') {
                        $m->sks = $sks_ketua;
                    } else {
                        $m->sks = $sks_anggota;
                    }
                    $m->save();
                }
            }
        }
    }
}
