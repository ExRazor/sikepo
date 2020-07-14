<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\TeacherPublication;
use App\Models\StudyProgram;

class TeacherPublicationStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $data = TeacherPublication::all();

        foreach($data as $d) {
            for($i=0;$i<rand(0,5);$i++) {
                DB::table('teacher_publication_students')->insert([
                    'id_publikasi'      => $d->id,
                    'nim'               => rand(111111111,666666666),
                    'nama'              => $faker->name,
                    'kd_prodi'          => StudyProgram::all()->random()->kd_prodi,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
