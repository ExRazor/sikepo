<?php

use Illuminate\Database\Seeder;
use App\Student;

class StudentForeignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<50;$i++){
            $student = Student::all()->random()->nim;
            $durasi  = ['Full-time','Part-time'];
            $asal    = ['Amerika','Eropa','Arab','Prancis','Malaysia','Singapur','Thailand','Filipin'];

            DB::table('student_foreigns')->insert([
                'nim'        => $student,
                'asal_negara'=> $asal[array_rand($asal)],
                'durasi'     => $durasi[array_rand($durasi)],
                'created_at' => now()
            ]);

            $data = Student::find($student);
            $data->kewarganegaraan = 'WNA';
            $data->save();
        }
    }
}
