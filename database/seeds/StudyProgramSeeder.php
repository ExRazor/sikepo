<?php

use Illuminate\Database\Seeder;
use App\Department;
use Faker\Factory as Faker;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('study_programs')->insert([
            [
                'kd_prodi' => 57201,
                'kd_jurusan' => 57401,
                'nama' => 'Sistem Informasi',
                'singkatan' => 'SISFO',
                'jenjang' => 'S1',
                'no_sk' => 'PTN/20/2203/1229854/INFOR/UNG',
                'tgl_sk' => '2008/04/20',
                'pejabat_sk' => 'Kurniawan Salih, S.T, M.T',
                'thn_menerima' => '2008',
                'nip_kaprodi' => '198004172002122002',
                'nm_kaprodi' => 'Lillyan Hadjaratie',
                'created_at' => now()
            ],
            [
                'kd_prodi' => 53242,
                'kd_jurusan' => 57401,
                'nama' => 'Pendidikan Teknologi Informasi',
                'singkatan' => 'PTI',
                'jenjang' => 'S1',
                'no_sk' => 'PTN/32/2008/1034567/INFOR/UNG',
                'tgl_sk' => '2013/06/14',
                'pejabat_sk' => 'Hermawan Prasetyo, M.Eng',
                'thn_menerima' => '2013',
                'nip_kaprodi' => '197511242001121001',
                'nm_kaprodi' => 'Dian Novian',
                'created_at' => now()
            ],
        ]);

        $faker = Faker::create('id_ID');
        $jenjang = ['D3','S1','S2','S3'];

        for($i = 0; $i < 20; $i++){
            DB::table('study_programs')->insert([
                [
                    'kd_prodi'      => rand(22222, 59999),
                    'kd_jurusan'    => Department::all()->random()->kd_jurusan,
                    'nama'          => 'Program Studi Random '.$i,
                    'singkatan'     => 'PSR'.$i,
                    'jenjang'       => $jenjang[array_rand($jenjang)],
                    'nip_kaprodi'   => rand(197201011982010101, 199001012000010101),
                    'nm_kaprodi'    => $faker->name,
                    'created_at'    => now()
                ],
            ]);
        }
    }
}
