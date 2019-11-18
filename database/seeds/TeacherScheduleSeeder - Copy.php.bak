<?php

use App\AcademicYear;
use App\Curriculum;
use App\StudyProgram;
use Illuminate\Database\Seeder;
use App\TeacherSchedule;
use App\Teacher;

class TeacherScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jurusan = ['Dalam','Luar'];

        for($j = 0; $j < 100; $j++){

            $cek = $jurusan[array_rand($jurusan)];
            $bidang = ['1','0'];
            $tahun_akademik = AcademicYear::all()->random();

            if($cek=='Dalam') {
                $teacher = Teacher::whereHas(
                                'studyProgram', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            )->inRandomOrder()->first();

                $curriculum = Curriculum::all()->random();

                $sesuai = ($teacher->kd_prodi==$curriculum->kd_prodi ? '1' : '0');

                TeacherSchedule::updateOrCreate(
                    [
                        'id_ta'          => $tahun_akademik->id,
                        'nidn'           => $teacher->nidn,
                    ],
                    [
                        'kd_matkul'      => $curriculum->kd_matkul,
                        'sesuai_prodi'   => $sesuai,
                        'sesuai_bidang'  => $bidang[array_rand($bidang)],
                        'created_at'     => now()
                    ]
                );
            } else {
                $matkul = ['Kewirausahaan','Pengabdian','Elektronika','Marketing','Etika Bermasyarakat'];

                $teacher = Teacher::whereHas(
                                'studyProgram', function($query) {
                                    $query->where('kd_jurusan',setting('app_department_id'));
                                }
                            )->inRandomOrder()->first();
                $prodi  = StudyProgram::where('kd_prodi','!=',$teacher->kd_prodi)->inRandomOrder()->first();

                TeacherSchedule::updateorCreate(
                    [
                        'id_ta'          => $tahun_akademik->id,
                        'nidn'           => $teacher->nidn,
                    ],
                    [
                        'nm_matkul'      => $matkul[array_rand($matkul)],
                        'kd_prodi'       => $prodi->kd_prodi,
                        'sesuai_prodi'   => '0',
                        'sesuai_bidang'  => $bidang[array_rand($bidang)],
                        'created_at'     => now()
                    ]
                );
            }

        }

        $schedule = TeacherSchedule::whereNotNull('kd_matkul')->get();

        foreach($schedule as $s) {
            $bidang = ['1','0'];

            $sesuai = ($s->teacher->kd_prodi==$s->curriculum->kd_prodi ? '1' : '0');

            $s->nm_matkul       = null;
            $s->kd_prodi        = null;
            $s->sesuai_prodi    = $sesuai;
            $s->sesuai_bidang   = $bidang[array_rand($bidang)];
            $s->save();
        }



    }
}
