<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Publication;
use App\StudyProgram;

class PublicationStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $data = Publication::all();

        foreach($data as $d) {
            for($i=0;$i<rand(0,5);$i++) {
                DB::table('publication_students')->insert([
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
