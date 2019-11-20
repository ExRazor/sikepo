<?php

use Illuminate\Database\Seeder;
use App\CommunityService;
use App\Student;
use App\StudyProgram;

class CommunityServiceStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = CommunityService::all();

        foreach($data as $d) {
            for($i=0;$i<rand(0,5);$i++) {
                DB::table('community_service_students')->insert([
                    'id_pengabdian'     => $d->id,
                    'nim'               => Student::all()->random()->nim,
                ]);
            }
        }
    }
}
