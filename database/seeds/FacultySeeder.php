<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $fakultas = [
                    'Fakultas Ilmu Pendidikan',
                    'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                    'Fakultas Ilmu Sosial',
                    'Fakultas Sosial dan Budaya',
                    'Fakultas Teknik',
                    'Fakultas Pertanian',
                    'Pascasarjana',
                    'Fakultas Olahraga dan Kesehatan',
                    'Fakultas Ekonomi',
                    'Fakultas Hukum',
                    'Fakultas Perikanan',
                    'Pendidikan Profesi Guru',
                    'Fakultas Kedokteran',
                ];

        $singkatan = [
                    'FIP',
                    'FMIPA',
                    'FIS',
                    'FSB',
                    'FATEK',
                    'FAPERTA',
                    'PASCA',
                    'FOK',
                    'FEKON',
                    'FH',
                    'FPIK',
                    'PPG',
                    'FAKEDOK',
                ];


    	for($i = 0; $i < count($fakultas); $i++){
                // insert data ke table pegawai menggunakan Faker
            DB::table('faculties')->insert([
                'nama'                  => $fakultas[$i],
                'singkatan'             => $singkatan[$i],
                // 'nip_dekan'             => rand(197201011982010101, 199001012000010101),
                // 'nm_dekan'              => $faker->name,
                'created_at'            => now()
            ]);
        }
    }
}
