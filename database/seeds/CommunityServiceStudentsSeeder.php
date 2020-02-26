<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
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
        $faker = Faker::create('id_ID');
        $data = CommunityService::all();

        foreach($data as $d) {
            for($i=0;$i<rand(0,5);$i++)
            {
                $asal       = ['dalam','luar'];
                $rand_asal  = $asal[array_rand($asal)];

                if($rand_asal=='dalam') {
                    $nim        = Student::all()->random()->nim;
                    $nama_lain  = null;
                    $asal_lain  = null;
                } else {
                    $nim        = rand(111111111,999999999);
                    $nama_lain  = $faker->name;
                    $asal_lain  = 'Luar program studi';
                }

                DB::table('community_service_students')->insert([
                    'id_pengabdian'     => $d->id,
                    'nim'               => $nim,
                    'nama_lain'         => $nama_lain,
                    'asal_lain'         => $asal_lain,
                ]);
            }
        }
    }
}
