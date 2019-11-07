<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Research;
use App\Student;
use App\StudyProgram;

class ResearchStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $research = Research::all();

        foreach($research as $r) {
            for($i=0;$i<rand(0,5);$i++) {
                DB::table('research_students')->insert([
                    'id_penelitian'     => $r->id,
                    'nim'               => rand(111111111,666666666),
                    'nama'              => $faker->name,
                    'kd_prodi'          => StudyProgram::all()->random()->kd_prodi,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
