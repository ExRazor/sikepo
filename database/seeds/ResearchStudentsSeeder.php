<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Research;
use App\Models\Student;

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
        $research = Research::select('*')->get();

        foreach($research as $r) {
            for($i=0;$i<rand(0,5);$i++) {
                $asal       = ['dalam','luar'];
                $rand_asal  = $asal[array_rand($asal)];
                $nim        = Student::all()->random()->nim;

                if($rand_asal=='dalam') {
                    // $nim        = Student::all()->random()->nim;
                    $nama_lain  = null;
                    $asal_lain  = null;
                } else {
                    // $nim        = rand(111111111,999999999);
                    $nama_lain  = $faker->name;
                    $asal_lain  = 'Luar program studi';
                }

                DB::table('research_students')->insert([
                    'id_penelitian'     => $r->id,
                    'nim'               => $nim,
                    // 'nama_lain'         => $nama_lain,
                    // 'asal_lain'         => $asal_lain,
                ]);
            }
        }
    }
}
