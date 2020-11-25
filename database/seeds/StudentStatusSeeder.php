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
        $students = Student::where('angkatan', '<', '2016')->get();
        $status   = ['Nonaktif', 'Lulus', 'Dropout'];
        foreach ($students as $student) {
            $tahun = AcademicYear::where('tahun_akademik', '>=', '2016')->inRandomOrder()->first();
            $status_mhs  = $status[array_rand($status)];

            DB::table('student_statuses')->insert([
                'id_ta'                 => $tahun->id,
                'nim'                   => $student->nim,
                'status'                => $status_mhs,
                'ipk_terakhir'          => rand(200, 400) / 100,
                'created_at'            => now()
            ]);
        }
    }
}
