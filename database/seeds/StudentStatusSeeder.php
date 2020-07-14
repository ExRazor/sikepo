<?php

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Student;

class StudentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = Student::all();
        $status   = ['Aktif','Nonaktif','Lulus'];
        foreach($students as $student) {
            $tahun_masuk = AcademicYear::where('tahun_akademik','<','2014')->inRandomOrder()->first();
            $status_mhs  = $status[array_rand($status)];

            DB::table('student_statuses')->insert([
                'id_ta'                 => $tahun_masuk->id,
                'nim'                   => $student->nim,
                'status'                => 'Aktif',
                'created_at'            => now()
            ]);

            DB::table('students')->where('nim',$student->nim)->update([
                'angkatan'              => $tahun_masuk->tahun_akademik,
            ]);

            if($status_mhs!='Aktif') {
                DB::table('student_statuses')->insert([
                    'id_ta'                 => AcademicYear::where('tahun_akademik','>','2014')->inRandomOrder()->first()->id,
                    'nim'                   => $student->nim,
                    'status'                => $status_mhs,
                    'ipk_terakhir'          => rand(200, 400)/100,
                    'created_at'            => now()
                ]);
            }

        }
    }
}
