<?php

use Illuminate\Database\Seeder;
use App\Research;
use App\Student;

class ResearchStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $research = Research::all();

        foreach($research as $r) {
            for($i=0;$i<rand(0,5);$i++) {
                DB::table('research_students')->insert([
                    'id_penelitian'     => $r->id,
                    'nim'               => Student::all()->random()->nim,
                    'created_at'        => now()
                ]);
            }
        }
    }
}
