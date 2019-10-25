<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Faculty;
use App\Department;
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

        return redirect()->route('setting.general')->with('flash.message', 'Setelan berhasil diubah!')->with('flash.class', 'success');
    }
}
